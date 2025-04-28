<!-- resources/views/appointments/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Schedule New Appointment') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'doctor')
                        <div class="form-group row mb-3">
                            <label for="patient_id" class="col-md-4 col-form-label text-md-right">{{ __('Patient') }}</label>

                            <div class="col-md-6">
                                <select id="patient_id" name="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('patient_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'patient')
                        <div class="form-group row mb-3">
                            <label for="doctor_id" class="col-md-4 col-form-label text-md-right">{{ __('Doctor') }}</label>

                            <div class="col-md-6">
                                <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('doctor_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div class="form-group row mb-3">
                            <label for="appointment_date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                            <div class="col-md-6">
                                <input id="appointment_date" type="date" class="form-control @error('appointment_date') is-invalid @enderror" name="appointment_date" value="{{ old('appointment_date') }}" required>

                                @error('appointment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="appointment_time" class="col-md-4 col-form-label text-md-right">{{ __('Time') }}</label>

                            <div class="col-md-6">
                                <input id="appointment_time" type="time" class="form-control @error('appointment_time') is-invalid @enderror" name="appointment_time" value="{{ old('appointment_time') }}" required disabled>

                                <div id="doctor-hours" class="mt-2 alert alert-info" style="display:none;">
                                    <strong>Doctor's working hours:</strong> <span id="working-hours"></span>
                                </div>

                                @error('appointment_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'doctor')
                        <div class="form-group row mb-3">
                            <label for="fee" class="col-md-4 col-form-label text-md-right">{{ __('Consultation Fee') }}</label>

                            <div class="col-md-6">
                                <input id="fee" type="number" step="0.01" min="0" class="form-control @error('fee') is-invalid @enderror" name="fee" value="{{ old('fee') }}" required>

                                @error('fee')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @else
                        <!-- Hidden fee field for patients - will be set by the controller -->
                        <input type="hidden" name="fee" value="0">
                        @endif

                        <div class="form-group row mb-3">
                            <label for="notes" class="col-md-4 col-form-label text-md-right">{{ __('Notes') }}</label>

                            <div class="col-md-6">
                                <textarea id="notes" class="form-control @error('notes') is-invalid @enderror" name="notes">{{ old('notes') }}</textarea>

                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Schedule Appointment') }}
                                </button>
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
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
                doctorHours.className = 'mt-2 alert alert-info';
                doctorHours.style.display = 'block';

                // Set min and max time constraints based on doctor's hours
                timeInput.min = schedule.start_time.substring(0, 5); // "09:00"
                timeInput.max = schedule.end_time.substring(0, 5); // "17:00"
            } else {
                workingHoursSpan.textContent += " (Doctor not available on this day)";
                doctorHours.className = 'mt-2 alert alert-warning';
                doctorHours.style.display = 'block';
                timeInput.disabled = true;
            }
        } else {
            // No schedule found
            workingHoursSpan.textContent = "No schedule available for this day";
            doctorHours.className = 'mt-2 alert alert-warning';
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
@endsection
