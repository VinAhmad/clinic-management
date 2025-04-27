<!-- resources/views/medical-records/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Create Medical Record</h3>
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

                    <form method="POST" action="{{ route('medical-records.store') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="appointment_id" class="col-md-4 col-form-label text-md-right">Appointment</label>
                            <div class="col-md-6">
                                <select id="appointment_id" class="form-control @error('appointment_id') is-invalid @enderror" name="appointment_id" required>
                                    <option value="">Select Appointment</option>
                                    @foreach($appointments as $appointment)
                                        <option value="{{ $appointment->id }}" 
                                            {{ old('appointment_id', request('appointment_id')) == $appointment->id ? 'selected' : '' }}
                                            data-patient-id="{{ $appointment->patient_id }}"
                                            data-doctor-id="{{ $appointment->doctor_id }}">
                                            ID: {{ $appointment->id }} - {{ $appointment->patient->name ?? 'N/A' }} with Dr. {{ $appointment->doctor->name ?? 'N/A' }} on {{ date('M d, Y H:i', strtotime($appointment->appointment_date)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id') }}">
                        <input type="hidden" name="doctor_id" id="doctor_id" value="{{ old('doctor_id') }}">

                        <div class="form-group row mb-3">
                            <label for="diagnosis" class="col-md-4 col-form-label text-md-right">Diagnosis</label>
                            <div class="col-md-6">
                                <textarea id="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" name="diagnosis" rows="4" required>{{ old('diagnosis') }}</textarea>
                                @error('diagnosis')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="prescription" class="col-md-4 col-form-label text-md-right">Prescription</label>
                            <div class="col-md-6">
                                <textarea id="prescription" class="form-control @error('prescription') is-invalid @enderror" name="prescription" rows="4">{{ old('prescription') }}</textarea>
                                @error('prescription')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="treatment_plan" class="col-md-4 col-form-label text-md-right">Treatment Plan</label>
                            <div class="col-md-6">
                                <textarea id="treatment_plan" class="form-control @error('treatment_plan') is-invalid @enderror" name="treatment_plan" rows="4">{{ old('treatment_plan') }}</textarea>
                                @error('treatment_plan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Create Record
                                </button>
                                <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">
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
        const appointmentSelect = document.getElementById('appointment_id');
        const patientIdInput = document.getElementById('patient_id');
        const doctorIdInput = document.getElementById('doctor_id');
        
        // Set initial values if an appointment is selected
        if (appointmentSelect.value) {
            const selectedOption = appointmentSelect.options[appointmentSelect.selectedIndex];
            patientIdInput.value = selectedOption.getAttribute('data-patient-id');
            doctorIdInput.value = selectedOption.getAttribute('data-doctor-id');
        }
        
        // Update values when appointment changes
        appointmentSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                patientIdInput.value = selectedOption.getAttribute('data-patient-id');
                doctorIdInput.value = selectedOption.getAttribute('data-doctor-id');
            } else {
                patientIdInput.value = '';
                doctorIdInput.value = '';
            }
        });
    });
</script>
@endpush
@endsection