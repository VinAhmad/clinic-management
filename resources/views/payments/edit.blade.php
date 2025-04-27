<!-- resources/views/payments/edit.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Payment</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('payments.update', $payment->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row mb-3">
                            <label for="appointment_id" class="col-md-4 col-form-label text-md-right">Appointment</label>
                            <div class="col-md-6">
                                <select id="appointment_id" class="form-control @error('appointment_id') is-invalid @enderror" name="appointment_id" required>
                                    <option value="">Select Appointment</option>
                                    @foreach($appointments as $appointment)
                                        <option value="{{ $appointment->id }}" 
                                            {{ (old('appointment_id') ?? $payment->appointment_id) == $appointment->id ? 'selected' : '' }}
                                            data-patient-id="{{ $appointment->patient_id }}"
                                            data-doctor-id="{{ $appointment->doctor_id }}"
                                            data-fee="{{ $appointment->fee }}">
                                            ID: {{ $appointment->id }} - {{ $appointment->patient->name ?? 'N/A' }} with Dr. {{ $appointment->doctor->name ?? 'N/A' }} (Fee: ${{ $appointment->fee }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id') ?? $payment->patient_id }}">
                        <input type="hidden" name="doctor_id" id="doctor_id" value="{{ old('doctor_id') ?? $payment->doctor_id }}">

                        <div class="form-group row mb-3">
                            <label for="transaction_id" class="col-md-4 col-form-label text-md-right">Transaction ID</label>
                            <div class="col-md-6">
                                <input id="transaction_id" type="text" class="form-control @error('transaction_id') is-invalid @enderror" name="transaction_id" value="{{ old('transaction_id') ?? $payment->transaction_id }}" required>
                                @error('transaction_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="amount" class="col-md-4 col-form-label text-md-right">Amount</label>
                            <div class="col-md-6">
                                <input id="amount" type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') ?? $payment->amount }}" required>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="payment_method" class="col-md-4 col-form-label text-md-right">Payment Method</label>
                            <div class="col-md-6">
                                <select id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" name="payment_method" required>
                                    <!-- resources/views/payments/edit.blade.php (continued) -->
                                    <option value="">Select Payment Method</option>
                                    <option value="Cash" {{ (old('payment_method') ?? $payment->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Credit Card" {{ (old('payment_method') ?? $payment->payment_method) == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="Debit Card" {{ (old('payment_method') ?? $payment->payment_method) == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="Insurance" {{ (old('payment_method') ?? $payment->payment_method) == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                                    <option value="Bank Transfer" {{ (old('payment_method') ?? $payment->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="Pending" {{ (old('status') ?? $payment->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Completed" {{ (old('status') ?? $payment->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Failed" {{ (old('status') ?? $payment->status) == 'Failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="Refunded" {{ (old('status') ?? $payment->status) == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="payment_date" class="col-md-4 col-form-label text-md-right">Payment Date</label>
                            <div class="col-md-6">
                                <input id="payment_date" type="datetime-local" class="form-control @error('payment_date') is-invalid @enderror" name="payment_date" value="{{ old('payment_date') ?? date('Y-m-d\TH:i', strtotime($payment->payment_date)) }}" required>
                                @error('payment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Payment
                                </button>
                                <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-fill patient_id, doctor_id, and amount based on selected appointment
        $('#appointment_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            var patientId = selectedOption.data('patient-id');
            var doctorId = selectedOption.data('doctor-id');
            var fee = selectedOption.data('fee');
            
            $('#patient_id').val(patientId);
            $('#doctor_id').val(doctorId);
            $('#amount').val(fee);
        });
    });
</script>
@endpush
@endsection