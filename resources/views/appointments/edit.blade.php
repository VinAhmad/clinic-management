<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Edit Appointment
                        </h2>
                        <p class="text-white/80 mt-2">Update appointment details and scheduling</p>
                    </div>
                    <a href="{{ route('appointments.show', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Appointment
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Update Appointment Details</h3>
                    <p class="text-gray-600 mt-1">Modify the appointment information below</p>
                </div>

                <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    @if(Auth::user()->role === 'admin')
                        <!-- Patient Selection for Admin -->
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Patient <span class="text-red-500">*</span>
                            </label>
                            <select id="patient_id" name="patient_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('patient_id') border-red-500 @enderror" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Doctor Selection for Admin -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Doctor <span class="text-red-500">*</span>
                            </label>
                            <select id="doctor_id" name="doctor_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('doctor_id') border-red-500 @enderror" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }} ({{ $doctor->specialization ?? 'General Practitioner' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <!-- Read-only Patient/Doctor Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                    {{ $appointment->patient->name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                    Dr. {{ $appointment->doctor->name }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'patient')
                        <!-- Date and Time for Admin/Patient -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date <span class="text-red-500">*</span>
                                </label>
                                <input id="appointment_date" type="date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('appointment_date') border-red-500 @enderror" required>
                                @error('appointment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Time <span class="text-red-500">*</span>
                                </label>
                                <input id="appointment_time" type="time" name="appointment_time" value="{{ old('appointment_time', $appointment->appointment_date->format('H:i')) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('appointment_time') border-red-500 @enderror" required>
                                @error('appointment_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @else
                        <!-- Read-only Date/Time for Doctor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date & Time</label>
                            <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                {{ $appointment->appointment_date->format('M d, Y h:i A') }}
                            </div>
                            <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d') }}">
                            <input type="hidden" name="appointment_time" value="{{ $appointment->appointment_date->format('H:i') }}">
                        </div>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <!-- Fee for Admin -->
                        <div>
                            <label for="fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Consultation Fee ($) <span class="text-red-500">*</span>
                            </label>
                            <input id="fee" type="number" step="0.01" min="0" name="fee" value="{{ old('fee', $appointment->fee) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('fee') border-red-500 @enderror" required>
                            @error('fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif(Auth::user()->role === 'doctor')
                        <!-- Read-only Fee for Doctor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee</label>
                            <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                ${{ number_format($appointment->fee, 2) }}
                            </div>
                            <input type="hidden" name="fee" value="{{ $appointment->fee }}">
                        </div>
                    @else
                        <input type="hidden" name="fee" value="{{ $appointment->fee }}">
                    @endif

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('notes') border-red-500 @enderror" placeholder="Any additional notes or special requirements">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin')
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('status') border-red-500 @enderror" required>
                                <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="canceled" {{ old('status', $appointment->status) == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('appointments.show', $appointment) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-orange-700 transition duration-200">
                            Update Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
