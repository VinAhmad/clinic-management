<!-- resources/views/payments/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Payment') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('payments.update', $payment) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Appointment') }}</label>
                            <div class="col-md-6">
                                <input type="text" readonly class="form-control-plaintext" value="{{ $payment->appointment->appointment_date->format('M d, Y h:i A') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Patient') }}</label>
                            <div class="col-md-6">
                                <input type="text" readonly class="form-control-plaintext" value="{{ $payment->patient->name }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Doctor') }}</label>
                            <div class="col-md-6">
                                <input type="text" readonly class="form-control-plaintext" value="Dr. {{ $payment->doctor->name }}">
                            </div>
                        </div>

                        @if(Auth::user()->role === 'admin')
                        <div class="form-group row mb-3">
                            <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>

                            <div class="col-md-6">
                                <input id="amount" type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $payment->amount) }}" required>

                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @else
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>
                            <div class="col-md-6">
                                <input type="text" readonly class="form-control-plaintext" value="${{ number_format($payment->amount, 2) }}">
                            </div>
                        </div>
                        @endif

                        <div class="form-group row mb-3">
                            <label for="payment_method" class="col-md-4 col-form-label text-md-right">{{ __('Payment Method') }}</label>

                            <div class="col-md-6">
                                <select id="payment_method" name="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method }}" {{ old('payment_method', $payment->payment_method) == $method ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $method)) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('payment_method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="transaction_id" class="col-md-4 col-form-label text-md-right">{{ __('Transaction ID') }}</label>

                            <div class="col-md-6">
                                <input id="transaction_id" type="text" class="form-control @error('transaction_id') is-invalid @enderror" name="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}">

                                @error('transaction_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if(Auth::user()->role === 'admin')
                        <div class="form-group row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

                            <div class="col-md-6">
                                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ old('status', $payment->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="refunded" {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>

                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Payment') }}
                                </button>
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection