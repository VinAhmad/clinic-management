<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Details') }}
        </h2>
    </x-slot>

    <div class="container mx-auto my-8">
        <h1 class="text-2xl font-bold">Payment ID: {{ $payment->id }}</h1>

        <div class="mt-4">
            <p><strong>Patient:</strong> {{ $payment->patient->name }}</p>
            <p><strong>Doctor:</strong> {{ $payment->doctor->name }}</p>
            <p><strong>Amount:</strong> ${{ $payment->amount }}</p>
            <p><strong>Status:</strong> {{ $payment->status }}</p>
            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date ?? 'N/A' }}</p>
        </div>

        @if ($payment->status == 'pending')
            <div class="mt-4">
                <a href="{{ route('payments.pay', $payment) }}" class="bg-green-500 text-black px-6 py-2 rounded">
                    Pay Now
                </a>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('payments.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded">
                Back to Payments
            </a>
        </div>
    </div>
</x-app-layout>
