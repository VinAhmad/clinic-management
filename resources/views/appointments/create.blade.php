<!-- resources/views/appointments/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Schedule New Appointment
                        </h2>
                        <p class="text-white/80 mt-2">Book a new appointment with your healthcare provider</p>
                    </div>
                    <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Appointments
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Appointment Details</h3>
                    <p class="text-gray-600 mt-1">Fill in the information below to schedule your appointment</p>
                </div>

                <form method="POST" action="{{ route('appointments.store') }}" class="p-6 space-y-6">
                    @csrf

                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'doctor')
                        <!-- Patient Selection -->
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Patient <span class="text-red-500">*</span>
                            </label>
                            <select id="patient_id" name="patient_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('patient_id') border-red-500 @enderror" required>
                                <option value="">Select a patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'patient')
                        <!-- Doctor Selection -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Doctor <span class="text-red-500">*</span>
                            </label>
                            <select id="doctor_id" name="doctor_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('doctor_id') border-red-500 @enderror" required>
                                <option value="">Select a doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Date and Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input id="appointment_date" type="date" name="appointment_date" value="{{ old('appointment_date') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('appointment_date') border-red-500 @enderror" required>
                            @error('appointment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Time <span class="text-red-500">*</span>
                            </label>
                            <input id="appointment_time" type="time" name="appointment_time" value="{{ old('appointment_time') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('appointment_time') border-red-500 @enderror" required disabled>

                            <div id="doctor-hours" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg" style="display:none;">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-blue-800 font-medium">Doctor's working hours:</span>
                                    <span id="working-hours" class="ml-2 text-blue-700"></span>
                                </div>
                            </div>

                            @error('appointment_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'doctor')
                        <!-- Fee -->
                        <div>
                            <label for="fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Consultation Fee ($) <span class="text-red-500">*</span>
                            </label>
                            <input id="fee" type="number" step="0.01" min="0" name="fee" value="{{ old('fee') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('fee') border-red-500 @enderror" required>
                            @error('fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="fee" value="0">
                    @endif

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('notes') border-red-500 @enderror" placeholder="Any additional notes or special requirements">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('appointments.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition duration-200">
                            Schedule Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get form elements
            const doctorSelect = document.getElementById('doctor_id');
            const dateInput = document.getElementById('appointment_date');
            const timeInput = document.getElementById('appointment_time');
            const doctorHours = document.getElementById('doctor-hours');
            const workingHoursSpan = document.getElementById('working-hours');

            // Store schedule data from PHP
            const schedulesData = @json($schedulesData ?? []);

            function updateScheduleInfo() {
                // Hide by default and disable time input
                doctorHours.style.display = 'none';
                timeInput.disabled = true;

                // Check if both doctor and date are selected
                if (!doctorSelect || !dateInput || !doctorSelect.value || !dateInput.value) {
                    return;
                }

                const doctorId = doctorSelect.value;
                const date = dateInput.value;

                // Get day of week from selected date
                const selectedDate = new Date(date);
                const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                const dayOfWeek = days[selectedDate.getDay()];

                // Check if we have schedule data for this doctor and day
                if (schedulesData[doctorId] && schedulesData[doctorId][dayOfWeek]) {
                    const schedule = schedulesData[doctorId][dayOfWeek];

                    // Format times for display
                    const startTime = formatTime(schedule.start_time);
                    const endTime = formatTime(schedule.end_time);

                    // Show schedule info
                    workingHoursSpan.textContent = `${startTime} - ${endTime}`;

                    // Enable time input only if doctor is available
                    if (schedule.is_available) {
                        timeInput.disabled = false;
                        doctorHours.className = 'mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg';
                        doctorHours.style.display = 'block';

                        // Set min and max time constraints based on doctor's hours
                        timeInput.min = schedule.start_time.substring(0, 5); // "09:00"
                        timeInput.max = schedule.end_time.substring(0, 5); // "17:00"
                    } else {
                        workingHoursSpan.textContent += " (Doctor not available on this day)";
                        doctorHours.className = 'mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg';
                        doctorHours.style.display = 'block';
                        timeInput.disabled = true;
                    }
                } else {
                    // No schedule found
                    workingHoursSpan.textContent = "No schedule available for this day";
                    doctorHours.className = 'mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg';
                    doctorHours.style.display = 'block';
                    timeInput.disabled = true;
                }
            }

            function formatTime(timeString) {
                if (!timeString) return '';

                // Convert "09:00:00" to "9:00 AM"
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const formattedHour = hour % 12 || 12;
                return `${formattedHour}:${minutes} ${ampm}`;
            }

            // Add event listeners
            if (doctorSelect) {
                doctorSelect.addEventListener('change', updateScheduleInfo);
            }

            if (dateInput) {
                dateInput.addEventListener('change', updateScheduleInfo);
            }

            // Run on page load if values are already set (e.g., when validation fails)
            updateScheduleInfo();
        });
    </script>
</x-app-layout>
