<!-- resources/views/payments/pay.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Payment') }}
        </h2>
    </x-slot>

    <div class="container mx-auto my-8">
        <h1 class="text-2xl font-bold">Confirm Payment for Appointment ID: {{ $payment->appointment_id }}</h1>

        <div class="mt-4">
            <p><strong>Amount:</strong> ${{ $payment->amount }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
        </div>

        <!-- Payment method selection -->
        <form method="POST" action="{{ route('payments.process', $payment) }}" class="mt-4">
            @csrf

            <!-- Payment Method Selection -->
            <label for="payment_method" class="block text-gray-700">Select Payment Method</label>
            <select name="payment_method" id="payment_method" class="mt-2 block w-full border-gray-300 rounded-lg">
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>

            <!-- If the payment status is 'paid', show the transaction ID field -->
            @if ($payment->status === 'paid')
                <div class="mt-4">
                    <label for="transaction_id" class="block text-gray-700">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="mt-2 block w-full border-gray-300 rounded-lg" placeholder="Enter transaction ID">
                </div>
            @endif

            <div class="mt-4">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded">
                    Confirm Payment
                </button>
            </div>
        </form>

        <div class="mt-6">
            <a href="{{ route('payments.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded">
                Back to Payments
            </a>
        </div>
    </div>
</x-app-layout>
