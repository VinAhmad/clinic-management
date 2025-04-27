<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\PDF;

class PaymentController extends Controller
{
    // Display all payments for the authenticated user (doctor or patient)
    public function index()
    {
        $user = Auth::user();
        $query = Payment::query();

        // Filter payments by user role
        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        } elseif ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        }

        // Retrieve payments and eager load doctor, patient, and appointment relationships
        $payments = $query->with(['doctor', 'patient', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }
    public function pay(Payment $payment)
    {
        $user = Auth::user();

    // Check if user has permission to make the payment
        if ($user->role === 'doctor' && $payment->doctor_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to make this payment.');
        }

        if ($user->role === 'patient' && $payment->patient_id !== $user->id) {
         return redirect()->route('dashboard')->with('error', 'You are not authorized to make this payment.');
        }

        return view('payments.pay', compact('payment')); // Payment view for user to confirm payment
    }

    // Show payment details for the given payment
    public function show(Payment $payment)
    {
        $user = Auth::user();

        // Check if user has permission to view this payment
        if ($user->role === 'doctor' && $payment->doctor_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this payment.');
        }

        if ($user->role === 'patient' && $payment->patient_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this payment.');
        }

        $payment->load(['doctor', 'patient', 'appointment']);

        return view('payments.show', compact('payment'));
    }

    // Show the edit form for the given payment
    public function edit(Payment $payment)
    {
        $user = Auth::user();

        // Only admin and patient can edit payments
        if ($user->role === 'doctor') {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to edit payments.');
        }

        // Patient can only edit their own pending payments
        if ($user->role === 'patient' && ($payment->patient_id !== $user->id || $payment->status !== 'pending')) {
            return redirect()->route('dashboard')->with('error', 'You can only edit your pending payments.');
        }

        $payment->load(['doctor', 'patient', 'appointment']);
        $paymentMethods = ['credit_card', 'debit_card', 'cash', 'insurance', 'bank_transfer'];

        return view('payments.edit', compact('payment', 'paymentMethods'));
    }

    // Update payment details
    public function update(Request $request, Payment $payment)
    {
        $user = Auth::user();

        // Validation
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);

        // Admin can update status and amount
        if ($user->role === 'admin') {
            $request->validate([
                'status' => 'required|in:pending,paid,refunded,failed',
                'amount' => 'required|numeric|min:0',
            ]);

            $validated['status'] = $request->status;
            $validated['amount'] = $request->amount;
        } else {
            // For patients, the payment status is automatically marked as 'paid'
            $validated['status'] = 'paid';
            $validated['payment_date'] = now();
        }

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)->with('success', 'Payment updated successfully.');
    }

    // Process a payment (usually for patient or doctor to mark a payment as paid)
// In your PaymentController.php

public function processPayment(Request $request, Payment $payment)
{
    // Validate payment method
    $validated = $request->validate([
        'payment_method' => 'required|in:cash,bank_transfer', // Only allow cash or bank_transfer
    ]);

    // If payment is not already marked as 'paid', proceed with payment processing
    if ($payment->status !== 'paid') {
        // Update the payment with selected method and mark it as 'paid'
        $payment->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'payment_date' => now(),
        ]);
    }

    // If transaction_id is provided, update the payment record with transaction ID
    if ($request->filled('transaction_id')) {
        $payment->update([
            'transaction_id' => $request->transaction_id,
        ]);
    }

    return redirect()->route('payments.show', $payment)
        ->with('success', 'Payment processed successfully.');
}


    // Show payment confirmation page
    public function confirmPayment(Payment $payment)
    {
        $user = Auth::user();

        // Check if the user has permission to confirm the payment
        if ($user->role === 'doctor' && $payment->doctor_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to process this payment.');
        }

        if ($user->role === 'patient' && $payment->patient_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to process this payment.');
        }

        return view('payments.confirm', compact('payment'));
    }

    // Generate invoice (PDF) for the payment
    public function generateInvoice(Payment $payment)
    {
        $user = Auth::user();

        // Check if user can generate this invoice
        if ($user->role === 'doctor' && $payment->doctor_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to generate this invoice.');
        }

        if ($user->role === 'patient' && $payment->patient_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to generate this invoice.');
        }

        $payment->load(['doctor', 'patient', 'appointment']);

        // Generate PDF invoice using DomPDF
        $pdf = PDF::loadView('payments.invoice_pdf', compact('payment'));
        $pdf->setPaper('a4', 'portrait');

        // Return the generated PDF for download
        $filename = 'invoice-' . $payment->id . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    // Generate payment reports (only accessible to admin or doctors)
    public function reports()
    {
        $user = Auth::user();

        // Only admin and doctors can view reports
        if ($user->role === 'patient') {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view payment reports.');
        }

        $query = Payment::query();

        // Filter payments based on user role
        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        }

        // Get monthly payment data
        $monthlyPayments = $query->selectRaw('MONTH(payment_date) as month, YEAR(payment_date) as year, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereNotNull('payment_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get total revenue
        $totalRevenue = $query->where('status', 'paid')->sum('amount');

        // Get pending payment amounts
        $pendingAmount = Payment::where('status', 'pending')->sum('amount');

        return view('payments.reports', compact('monthlyPayments', 'totalRevenue', 'pendingAmount'));
    }
}
