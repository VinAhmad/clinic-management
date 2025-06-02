<!-- resources/views/payments/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Edit Payment
                        </h2>
                        <p class="text-white/80 mt-2">Update payment #{{ $payment->id }} details</p>
                    </div>
                    <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Payment
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Update Payment Information</h3>
                    <p class="text-gray-600 mt-1">Modify the payment details below</p>
                </div>

                <form method="POST" action="{{ route('payments.update', $payment) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Payment Overview -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Overview</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                                    {{ $payment->patient->name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                                    Dr. {{ $payment->doctor->name }}
                                </div>
                            </div>
                            @if($payment->appointment)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Appointment</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                                        {{ $payment->appointment->appointment_date->format('F j, Y \a\t g:i A') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->role === 'admin')
                        <!-- Amount (Admin Only) -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Amount ($) <span class="text-red-500">*</span>
                            </label>
                            <input id="amount"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   name="amount"
                                   value="{{ old('amount', $payment->amount) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('amount') border-red-500 @enderror"
                                   required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <!-- Read-only Amount for Patients -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                            <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                                ${{ number_format($payment->amount, 2) }}
                            </div>
                        </div>
                    @endif

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method"
                                name="payment_method"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('payment_method') border-red-500 @enderror"
                                required>
                            <option value="">Select payment method</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" {{ old('payment_method', $payment->payment_method) == $method ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $method)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(Auth::user()->role === 'admin')
                        <!-- Status (Admin Only) -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status"
                                    name="status"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('status') border-red-500 @enderror"
                                    required>
                                <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('status', $payment->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="refunded" {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <!-- Current Status Display for Patients -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                            <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                                @if($payment->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($payment->status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                @elseif($payment->status == 'refunded')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Refunded
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('payments.show', $payment) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-orange-700 transition duration-200">
                            Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
