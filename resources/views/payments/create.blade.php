<!-- resources/views/payments/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Create New Payment
                        </h2>
                        <p class="text-white/80 mt-2">Generate payment record for appointment</p>
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
                    <p class="text-gray-600 mt-1">Create a payment record for an appointment</p>
                </div>

                <form method="POST" action="{{ route('payments.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Appointment Selection -->
                    <div>
                        <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Appointment <span class="text-red-500">*</span>
                        </label>
                        <select id="appointment_id" 
                                name="appointment_id" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('appointment_id') border-red-500 @enderror" 
                                required>
                            <option value="">Choose an appointment</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->id }}" 
                                        data-fee="{{ $appointment->fee }}"
                                        data-patient="{{ $appointment->patient->name }}"
                                        data-doctor="{{ $appointment->doctor->name }}"
                                        data-date="{{ $appointment->appointment_date->format('M d, Y g:i A') }}"
                                        {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                    {{ $appointment->patient->name }} - Dr. {{ $appointment->doctor->name }} 
                                    ({{ $appointment->appointment_date->format('M d, Y g:i A') }}) - ${{ number_format($appointment->fee, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('appointment_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Appointment Details (Auto-populated) -->
                    <div id="appointment-details" class="bg-gray-50 rounded-lg p-4" style="display: none;">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Appointment Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                                <div id="patient-name" class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                                <div id="doctor-name" class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                                <div id="appointment-date" class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-100"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee</label>
                                <div id="consultation-fee" class="px-3 py-2 border border-gray-300 rounded-lg bg-emerald-100 text-emerald-800 font-semibold"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method" 
                                name="payment_method" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('payment_method') border-red-500 @enderror" 
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

                    <!-- Payment Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror" 
                                required>
                            <option value="">Select status</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('payments.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition duration-200">
                            Create Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const appointmentSelect = document.getElementById('appointment_id');
            const appointmentDetails = document.getElementById('appointment-details');
            const patientName = document.getElementById('patient-name');
            const doctorName = document.getElementById('doctor-name');
            const appointmentDate = document.getElementById('appointment-date');
            const consultationFee = document.getElementById('consultation-fee');

            appointmentSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value) {
                    // Show appointment details
                    appointmentDetails.style.display = 'block';
                    
                    // Populate fields with data from selected option
                    patientName.textContent = selectedOption.getAttribute('data-patient');
                    doctorName.textContent = 'Dr. ' + selectedOption.getAttribute('data-doctor');
                    appointmentDate.textContent = selectedOption.getAttribute('data-date');
                    consultationFee.textContent = '$' + parseFloat(selectedOption.getAttribute('data-fee')).toFixed(2);
                } else {
                    // Hide appointment details
                    appointmentDetails.style.display = 'none';
                }
            });

            // Trigger change event if there's a pre-selected value (for validation errors)
            if (appointmentSelect.value) {
                appointmentSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>
