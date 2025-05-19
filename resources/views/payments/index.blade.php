<!-- resources/views/payments/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>{{ __('Payments') }}</h2>
        </div>
        @if(Auth::user()->role === 'admin')
        <div class="col-md-4 text-end">
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('New Payment') }}
            </a>
            <a href="{{ route('payments.reports') }}" class="btn btn-info">
                <i class="fas fa-chart-line"></i> {{ __('Reports') }}
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($payments->isEmpty())
                <div class="alert alert-info">
                    {{ __('No payments found.') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Patient') }}</th>
                                <th>{{ __('Doctor') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Method') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td>{{ $payment->patient->name }}</td>
                                    <td>Dr. {{ $payment->doctor->name }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @if($payment->status == 'pending')
                                            <span class="badge bg-warning">{{ __('Pending') }}</span>
                                        @elseif($payment->status == 'paid')
                                            <span class="badge bg-success">{{ __('Paid') }}</span>
                                        @elseif($payment->status == 'refunded')
                                            <span class="badge bg-info">{{ __('Refunded') }}</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="badge bg-danger">{{ __('Failed') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_method ? ucwords(str_replace('_', ' ', $payment->payment_method)) : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'patient' && Auth::id() === $payment->patient_id && $payment->status === 'pending'))
                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif

                                        @if($payment->status === 'paid')
                                            <a href="{{ route('payments.view-invoice', $payment) }}" class="btn btn-sm btn-success" title="View Invoice">
                                                <i class="fas fa-file-invoice"></i> Invoice
                                            </a>
                                        @endif

                                        @if(Auth::user()->role === 'patient' && $payment->status === 'pending')
                                            <a href="{{ route('payments.process', $payment) }}" class="btn btn-sm btn-warning" title="Pay Now">
                                                <i class="fas fa-credit-card"></i> Pay Now
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
