<!-- resources/views/payments/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Create Payment
                        </h2>
                        <p class="text-white/80 mt-2">Add a new payment record to the system</p>
                    </div>
                    <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Payments
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Payment Information</h3>
                    <p class="text-gray-600 mt-1">Fill in the payment details below</p>
                </div>

                <form method="POST" action="{{ route('payments.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Appointment Selection -->
                    <div>
                        <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Appointment <span class="text-red-500">*</span>
                        </label>
                        <select id="appointment_id" 
                                name="appointment_id" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('appointment_id') border-red-500 @enderror" 
                                required>
                            <option value="">Select an appointment</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                    {{ $appointment->patient->name }} - Dr. {{ $appointment->doctor->name }} - {{ $appointment->appointment_date->format('M d, Y h:i A') }}
                                </option>
                            @endforeach
                        </select>
                        @error('appointment_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Amount ($) <span class="text-red-500">*</span>
                        </label>
                        <input id="amount" 
                               type="number" 
                               step="0.01" 
                               min="0" 
                               name="amount" 
                               value="{{ old('amount') }}" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('amount') border-red-500 @enderror" 
                               placeholder="0.00"
                               required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method" 
                                name="payment_method" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('payment_method') border-red-500 @enderror" 
                                required>
                            <option value="">Select payment method</option>
                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="insurance" {{ old('payment_method') == 'insurance' ? 'selected' : '' }}>Insurance</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transaction ID -->
                    <div>
                        <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction ID
                        </label>
                        <input id="transaction_id" 
                               type="text" 
                               name="transaction_id" 
                               value="{{ old('transaction_id') }}" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('transaction_id') border-red-500 @enderror" 
                               placeholder="Optional transaction reference">
                        @error('transaction_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('status') border-red-500 @enderror" 
                                required>
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ old('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('payments.index') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition duration-200">
                            Create Payment
                        </button>
                    </div>
                </form>
            </div>

            @if($appointments->isEmpty())
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">No Available Appointments</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                There are no scheduled appointments without payments. All appointments either have payments or are not scheduled yet.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
