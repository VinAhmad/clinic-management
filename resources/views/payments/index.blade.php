<!-- resources/views/payments/index.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Payments</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Create New Payment
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->patient->name ?? 'N/A' }}</td>
                                <td>{{ $payment->doctor->name ?? 'N/A' }}</td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>
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
                                </td>
                                <td>{{ $payment->payment_date }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No payments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection