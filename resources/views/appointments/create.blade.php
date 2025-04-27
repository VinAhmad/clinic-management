<!-- resources/views/appointments/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Create New Appointment</h3>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="patient_id" class="col-md-4 col-form-label text-md-right">Patient</label>
                            <div class="col-md-6">
                                <select id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" name="patient_id" required>
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

                        <div class="form-group row mb-3">
                            <label for="doctor_id" class="col-md-4 col-form-label text-md-right">Doctor</label>
                            <div class="col-md-6">
                                <select id="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" name="doctor_id" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }} ({{ $doctor->specialization }})
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

                        <div class="form-group row mb-3">
                            <label for="appointment_date" class="col-md-4 col-form-label text-md-right">Appointment Date</label>
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
                            <label for="appointment_time" class="col-md-4 col-form-label text-md-right">Time Slot</label>
                            <div class="col-md-6">
                                <select id="appointment_time" class="form-control @error('appointment_time') is-invalid @enderror" name="appointment_time" required>
                                    <option value="">Select a doctor and date first</option>
                                </select>
                                @error('appointment_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="fee" class="col-md-4 col-form-label text-md-right">Fee</label>
                            <div class="col-md-6">
                                <input id="fee" type="number" step="0.01" class="form-control @error('fee') is-invalid @enderror" name="fee" value="{{ old('fee') }}" required>
                                @error('fee')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="notes" class="col-md-4 col-form-label text-md-right">Notes</label>
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
                                    Create Appointment
                                </button>
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const doctorSelect = document.getElementById('doctor_id');
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');
        
        // Function to fetch available time slots
        function fetchTimeSlots() {
            const doctorId = doctorSelect.value;
            const date = dateInput.value;
            
            if (!doctorId || !date) {
                timeSelect.innerHTML = '<option value="">Select a doctor and date first</option>';
                return;
            }
            
            // Show loading indicator
            timeSelect.innerHTML = '<option value="">Loading available slots...</option>';
            
            // Fetch available slots from the server
            fetch(`{{ route('appointments.slots') }}?doctor_id=${doctorId}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    timeSelect.innerHTML = '<option value="">Select Time Slot</option>';
                    
                    if (data.slots && data.slots.length > 0) {
                        data.slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.start;
                            option.textContent = slot.formatted;
                            timeSelect.appendChild(option);
                        });
                    } else {
                        timeSelect.innerHTML = '<option value="">No available slots for this day</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching time slots:', error);
                    timeSelect.innerHTML = '<option value="">Error loading slots, please try again</option>';
                });
        }
        
        // Add event listeners to trigger slot fetching
        doctorSelect.addEventListener('change', fetchTimeSlots);
        dateInput.addEventListener('change', fetchTimeSlots);
    });
</script>
@endpush
@endsection