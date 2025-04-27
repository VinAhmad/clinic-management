<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Payment') }}
        </h2>
    </x-slot>

    <div class="container mx-auto my-8">
        <h1 class="text-2xl font-bold">Confirm Payment for Payment ID: {{ $payment->id }}</h1>

        <div class="mt-4">
            <p><strong>Amount:</strong> ${{ $payment->amount }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
        </div>

        <form method="POST" action="{{ route('payments.process', $payment) }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-green-500 text-black px-6 py-2 rounded">
                Confirm Payment
            </button>
        </form>

        <div class="mt-6">
            <a href="{{ route('payments.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded">
                Back to Payments
            </a>
        </div>
    </div>
</x-app-layout>
