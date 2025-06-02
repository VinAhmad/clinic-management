<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            ->orderBy('created_at', 'asc')
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
        ]);

        $payment->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'payment_date' => now(),
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment processed successfully.');
    }

    public function viewInvoice(Payment $payment, Request $request)
    {
        $user = Auth::user();

        // Check if user has permission to view this payment
        if ($user->role === 'doctor' && $payment->doctor_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view this invoice.');
        }

        if ($user->role === 'patient' && $payment->patient_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view this invoice.');
        }

        $payment->load(['doctor', 'patient', 'appointment']);

        // If download parameter is provided, generate and download PDF
        if ($request->has('download')) {
            // Generate PDF invoice using the DOMPDF library
            $pdf = PDF::loadView('payments.invoice_pdf', compact('payment'));

            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');

            // Generate unique filename for the invoice
            $filename = 'invoice-' . $payment->id . '-' . date('Y-m-d') . '.pdf';

            // Return the PDF for download
            return $pdf->download($filename);
        }

        // Otherwise just show the invoice view
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

        try {
            $query = Payment::query();

            if ($user->role === 'doctor') {
                $query->where('doctor_id', $user->id);
            }

            // Get total revenue from paid payments with null check
            $totalRevenue = (clone $query)->where('status', 'paid')->sum('amount') ?? 0;

            // Get pending payments amount with separate query for accuracy
            $pendingQuery = Payment::query();
            if ($user->role === 'doctor') {
                $pendingQuery->where('doctor_id', $user->id);
            }
            $pendingAmount = $pendingQuery->where('status', 'pending')->sum('amount') ?? 0;

            // Get payments by month with improved database compatibility
            $connection = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);

            $monthlyPayments = collect();

            if ($connection === 'sqlite') {
                // SQLite version with better error handling
                $monthlyPayments = (clone $query)
                    ->selectRaw("CAST(strftime('%m', payment_date) AS INTEGER) as month, CAST(strftime('%Y', payment_date) AS INTEGER) as year, SUM(amount) as total")
                    ->where('status', 'paid')
                    ->whereNotNull('payment_date')
                    ->where('payment_date', '!=', '')
                    ->where('payment_date', '!=', '0000-00-00')
                    ->groupByRaw("strftime('%Y', payment_date), strftime('%m', payment_date)")
                    ->orderByRaw("strftime('%Y', payment_date) DESC, strftime('%m', payment_date) DESC")
                    ->get();
            } else {
                // MySQL version with better error handling
                $monthlyPayments = (clone $query)
                    ->selectRaw('MONTH(payment_date) as month, YEAR(payment_date) as year, SUM(amount) as total')
                    ->where('status', 'paid')
                    ->whereNotNull('payment_date')
                    ->where('payment_date', '!=', '0000-00-00')
                    ->groupByRaw('YEAR(payment_date), MONTH(payment_date)')
                    ->orderByRaw('YEAR(payment_date) DESC, MONTH(payment_date) DESC')
                    ->get();
            }

            // Ensure numeric values and filter out invalid data
            $monthlyPayments = $monthlyPayments->filter(function ($payment) {
                return $payment->month >= 1 && $payment->month <= 12 && $payment->year > 0;
            })->map(function ($payment) {
                $payment->month = (int) $payment->month;
                $payment->year = (int) $payment->year;
                $payment->total = (float) $payment->total;
                return $payment;
            });

        } catch (\Exception $e) {
            // If there's an error with the query, provide default values
            $monthlyPayments = collect();
            $totalRevenue = 0;
            $pendingAmount = 0;

            // Log the error for debugging
            Log::error('Error in payment reports: ' . $e->getMessage());

            // Optionally show a user-friendly message
            session()->flash('warning', 'Some report data may be incomplete due to a system issue.');
        }

        return view('payments.reports', compact('monthlyPayments', 'totalRevenue', 'pendingAmount'));
    }

    public function create()
    {
        $user = Auth::user();

        // Only admin can create payments directly
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to create payments.');
        }

        // Get appointments that don't have payments yet
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDoesntHave('payment')
            ->where('status', 'scheduled')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('payments.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Only admin can create payments directly
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to create payments.');
        }

        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'payment_method' => 'required|string',
            'status' => 'required|in:pending,paid,refunded,failed',
        ]);

        // Get the appointment to extract patient, doctor info and fee
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($validated['appointment_id']);

        // Check if payment already exists for this appointment
        if ($appointment->payment) {
            return redirect()->route('payments.index')
                ->with('error', 'Payment already exists for this appointment.');
        }

        // Create the payment
        $paymentData = [
            'appointment_id' => $validated['appointment_id'],
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'amount' => $appointment->fee, // Use appointment's consultation fee
            'payment_method' => $validated['payment_method'],
            'status' => $validated['status'],
        ];

        // Set payment_date if status is paid
        if ($validated['status'] === 'paid') {
            $paymentData['payment_date'] = now();
        }

        $payment = Payment::create($paymentData);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment created successfully.');
    }
}
