<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Payment::query();

        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        } elseif ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        }

        $payments = $query->with(['doctor', 'patient', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();

        // Check if user has permission to view this payment
        if ($user->role === 'doctor' && $payment->doctor_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view this payment.');
        }

        if ($user->role === 'patient' && $payment->patient_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view this payment.');
        }

        $payment->load(['doctor', 'patient', 'appointment']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $user = Auth::user();

        // Only admin and patient can edit payments
        if ($user->role === 'doctor') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to edit payments.');
        }

        // Patient can only edit their own pending payments
        if ($user->role === 'patient' &&
            ($payment->patient_id !== $user->id || $payment->status !== 'pending')) {
            return redirect()->route('dashboard')
                ->with('error', 'You can only edit your pending payments.');
        }

        $payment->load(['doctor', 'patient', 'appointment']);
        $paymentMethods = ['credit_card', 'debit_card', 'cash', 'insurance', 'bank_transfer'];

        return view('payments.edit', compact('payment', 'paymentMethods'));
    }

    public function update(Request $request, Payment $payment)
    {
        $user = Auth::user();

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
            // Patient is making a payment
            $validated['status'] = 'paid';
            $validated['payment_date'] = now();
        }

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function processPayment(Request $request, Payment $payment)
    {
        // This would integrate with a payment gateway in a real application
        // For now, we'll just mark the payment as paid

        $validated = $request->validate([
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);

        $payment->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'payment_date' => now(),
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment processed successfully.');
    }

    public function generateInvoice(Payment $payment)
    {
        $user = Auth::user();

        // Check if user has permission to view this payment
        if ($user->role === 'doctor' && $payment->doctor_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to generate this invoice.');
        }

        if ($user->role === 'patient' && $payment->patient_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to generate this invoice.');
        }

        $payment->load(['doctor', 'patient', 'appointment']);

        // In a real application, you might use a PDF library to generate a proper invoice

        return view('payments.invoice', compact('payment'));
    }

    public function reports()
    {
        $user = Auth::user();

        // Only admin and doctors can view reports
        if ($user->role === 'patient') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view payment reports.');
        }

        $query = Payment::query();

        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        }

        // Get payments by month
        $monthlyPayments = $query->selectRaw('MONTH(payment_date) as month, YEAR(payment_date) as year, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereNotNull('payment_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get total revenue
        $totalRevenue = $query->where('status', 'paid')->sum('amount');

        // Get pending payments
        $pendingAmount = Payment::where('status', 'pending')->sum('amount');

        return view('payments.reports', compact('monthlyPayments', 'totalRevenue', 'pendingAmount'));
    }
}
