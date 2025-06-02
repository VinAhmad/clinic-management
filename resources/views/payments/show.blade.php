<!-- resources/views/payments/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Payment Details
                        </h2>
                        <p class="text-white/80 mt-2">Payment #{{ $payment->id }} information</p>
                    </div>
                    <div class="flex space-x-3">
                        @if($payment->status === 'paid')
                            <a href="{{ route('payments.view-invoice', $payment) }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                Download Invoice
                            </a>
                        @endif
                        <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Payment Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                                @if($payment->status == 'paid')
                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($payment->status == 'pending')
                                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Payment #{{ $payment->id }}</h3>
                                <p class="text-gray-600">Created on {{ $payment->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</div>
                            @if($payment->status == 'paid')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Paid
                                </span>
                            @elseif($payment->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif($payment->status == 'refunded')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Refunded
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Failed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Payment Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h4>
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Payment Method</label>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $payment->payment_method ? ucwords(str_replace('_', ' ', $payment->payment_method)) : 'Not specified' }}
                                    </p>
                                </div>

                                @if($payment->transaction_id)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="text-sm font-medium text-gray-500">Transaction ID</label>
                                        <p class="text-sm font-semibold text-gray-900">{{ $payment->transaction_id }}</p>
                                    </div>
                                @endif

                                @if($payment->payment_date)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="text-sm font-medium text-gray-500">Payment Date</label>
                                        <p class="text-sm font-semibold text-gray-900">{{ $payment->payment_date->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Appointment Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Appointment Details</h4>
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Patient</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ $payment->patient->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $payment->patient->email }}</p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Doctor</label>
                                    <p class="text-sm font-semibold text-gray-900">Dr. {{ $payment->doctor->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $payment->doctor->specialization ?? 'General Practitioner' }}</p>
                                </div>

                                @if($payment->appointment)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="text-sm font-medium text-gray-500">Appointment Date</label>
                                        <p class="text-sm font-semibold text-gray-900">{{ $payment->appointment->appointment_date->format('F j, Y \a\t g:i A') }}</p>
                                        <a href="{{ route('appointments.show', $payment->appointment) }}" class="text-xs text-emerald-600 hover:text-emerald-800">View Appointment</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if(Auth::user()->role === 'patient' && $payment->status === 'pending')
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-amber-800 mb-2">Payment Required</h3>
                                        <p class="text-amber-700 mb-4">This payment is still pending. Click the button below to complete your payment.</p>
                                        <form action="{{ route('payments.process', $payment) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="credit_card">
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-green-700 transition">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                                Pay Now - ${{ number_format($payment->amount, 2) }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'patient' && $payment->status === 'pending'))
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('payments.edit', $payment) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                                    Edit Payment
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
