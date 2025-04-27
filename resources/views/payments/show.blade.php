<!-- resources/views/payments/show.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Payment Details</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">ID:</div>
                        <div class="col-md-8">{{ $payment->id }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Patient:</div>
                        <div class="col-md-8">{{ $payment->patient->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Doctor:</div>
                        <div class="col-md-8">{{ $payment->doctor->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Appointment:</div>
                        <div class="col-md-8">
                            @if($payment->appointment)
                                ID: {{ $payment->appointment->id }} on {{ $payment->appointment->appointment_date }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Transaction ID:</div>
                        <div class="col-md-8">{{ $payment->transaction_id }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Amount:</div>
                        <div class="col-md-8">${{ number_format($payment->amount, 2) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Payment Method:</div>
                        <div class="col-md-8">{{ $payment->payment_method }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Status:</div>
                        <div class="col-md-8">
                            @if($payment->status == 'Completed')
                                <span class="badge bg-success">{{ $payment->status }}</span>
                            @elseif($payment->status == 'Pending')
                                <span class="badge bg-warning">{{ $payment->status }}</span>
                            @elseif($payment->status == 'Failed')
                                <span class="badge bg-danger">{{ $payment->status }}</span>
                            @elseif($payment->status == 'Refunded')
                                <span class="badge bg-info">{{ $payment->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $payment->status }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Payment Date:</div>
                        <div class="col-md-8">{{ $payment->payment_date }}</div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-8 offset-md-4">
                            <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection