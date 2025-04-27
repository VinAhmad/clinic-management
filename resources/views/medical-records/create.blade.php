<!-- resources/views/medical-records/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Medical Record') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('medical-records.store') }}">
                        @csrf

                        @if(isset($appointment))
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <div class="form-group row mb-3">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Patient') }}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly class="form-control-plaintext" value="{{ $appointment->patient->name }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Appointment Date') }}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly class="form-control-plaintext" value="{{ $appointment->appointment_date->format('M d, Y h:i A') }}">
                                </div>
                            </div>
                        @else
                            <div class="form-group row mb-3">
                                <label for="appointment_id" class="col-md-4 col-form-label text-md-right">{{ __('Appointment') }}</label>
                                <div class="col-md-6">
                                    <select id="appointment_id" name="appointment_id" class="form-control @error('appointment_id') is-invalid @enderror" required>
                                        <option value="">Select Appointment</option>
                                        @foreach($appointments as $appointment)
                                            <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                                {{ $appointment->patient->name }} - {{ $appointment->appointment_date->format('M d, Y h:i A') }}
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
                        @endif

                        <div class="form-group row mb-3">
                            <label for="diagnosis" class="col-md-4 col-form-label text-md-right">{{ __('Diagnosis') }}</label>

                            <div class="col-md-6">
                                <textarea id="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" name="diagnosis" required rows="3">{{ old('diagnosis') }}</textarea>

                                @error('diagnosis')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="prescription" class="col-md-4 col-form-label text-md-right">{{ __('Prescription') }}</label>

                            <div class="col-md-6">
                                <textarea id="prescription" class="form-control @error('prescription') is-invalid @enderror" name="prescription" required rows="3">{{ old('prescription') }}</textarea>

                                @error('prescription')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="treatment_plan" class="col-md-4 col-form-label text-md-right">{{ __('Treatment Plan') }}</label>

                            <div class="col-md-6">
                                <textarea id="treatment_plan" class="form-control @error('treatment_plan') is-invalid @enderror" name="treatment_plan" rows="3">{{ old('treatment_plan') }}</textarea>

                                @error('treatment_plan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="next_appointment" class="col-md-4 col-form-label text-md-right">{{ __('Next Appointment') }}</label>

                            <div class="col-md-6">
                                <input id="next_appointment" type="date" class="form-control @error('next_appointment') is-invalid @enderror" name="next_appointment" value="{{ old('next_appointment') }}">

                                @error('next_appointment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Record') }}
                                </button>
                                <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">
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