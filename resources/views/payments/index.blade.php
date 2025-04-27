<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="container mx-auto my-8">
        <h1 class="text-2xl font-bold">Payments History</h1>

        @if ($payments->isEmpty())
            <p>No payments found.</p>
        @else
            <table class="min-w-full mt-4 border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-left">Patient</th>
                        <th class="py-2 px-4 text-left">Doctor</th>
                        <th class="py-2 px-4 text-left">Amount</th>
                        <th class="py-2 px-4 text-left">Status</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $payment->id }}</td>
                            <td class="py-2 px-4">{{ $payment->patient->name }}</td>
                            <td class="py-2 px-4">{{ $payment->doctor->name }}</td>
                            <td class="py-2 px-4">${{ $payment->amount }}</td>
                            <td class="py-2 px-4">{{ $payment->status }}</td>
                            <td class="py-2 px-4 text-center">
                                @if ($payment->status == 'pending')
                                    <a href="{{ route('payments.pay', $payment) }}" class="bg-green-500 text-black px-3 py-1 rounded">Pay</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                <a href="{{ route('appointments.create') }}" class="bg-green-500 text-black px-6 py-2 rounded">
                    Make a New Appointment
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
