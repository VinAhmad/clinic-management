<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Payment') }}
        </h2>
    </x-slot>

    <div class="container mx-auto my-8">
        <h1 class="text-2xl font-bold">Edit Payment</h1>

        <form method="POST" action="{{ route('payments.update', $payment) }}" class="mt-4">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method }}" {{ $payment->payment_method === $method ? 'selected' : '' }}>
                            {{ ucfirst($method) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="transaction_id">Transaction ID</label>
                <input type="text" name="transaction_id" id="transaction_id" class="form-control" value="{{ $payment->transaction_id }}">
            </div>

            <div class="form-group">
                <label for="status">Payment Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $payment->status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="refunded" {{ $payment->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="failed" {{ $payment->status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" value="{{ $payment->amount }}">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded mt-4">Update Payment</button>
        </form>

        <div class="mt-6">
            <a href="{{ route('payments.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded">
                Back to Payments
            </a>
        </div>
    </div>
</x-app-layout>
