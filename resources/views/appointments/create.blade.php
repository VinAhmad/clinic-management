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
                            <label for="appointment_time" class="col-md-4 col-form-label text-md-right">{{ __('Time Slot') }}</label>

                            <div class="col-md-6">
                                <select id="appointment_time" name="appointment_time" class="form-control @error('appointment_time') is-invalid @enderror" required disabled>
                                    <option value="">Select Date & Doctor First</option>
                                </select>

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

@section('scripts')
<script>
    $(document).ready(function() {
        // When doctor and date are selected, fetch available time slots
        function loadTimeSlots() {
            const doctorId = $('#doctor_id').val();
            const date = $('#appointment_date').val();

            if (doctorId && date) {
                $.ajax({
                    url: "{{ route('appointments.slots') }}",
                    type: "GET",
                    data: {
                        doctor_id: doctorId,
                        date: date
                    },
                    success: function(response) {
                        const timeSlotSelect = $('#appointment_time');
                        timeSlotSelect.empty();
                        timeSlotSelect.prop('disabled', false);

                        if (response.slots.length > 0) {
                            timeSlotSelect.append('<option value="">Select Time Slot</option>');

                            response.slots.forEach(function(slot) {
                                timeSlotSelect.append(`<option value="${slot.start}">${slot.formatted}</option>`);
                            });
                        } else {
                            timeSlotSelect.append('<option value="">No available slots</option>');
                        }
                    }
                });
            }
        }

        $('#doctor_id, #appointment_date').change(loadTimeSlots);
    });
</script>
@endsection

@endsection
