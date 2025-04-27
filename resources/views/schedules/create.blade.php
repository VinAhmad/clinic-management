<!-- resources/views/schedules/create.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Create New Schedule</h3>
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
                    <form method="POST" action="{{ route('schedules.store') }}">
                        @csrf
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
                            <label for="day" class="col-md-4 col-form-label text-md-right">Day</label>
                            <div class="col-md-6">
                                <select id="day" class="form-control @error('day') is-invalid @enderror" name="day" required>
                                    <option value="">Select Day</option>
                                    <option value="Monday" {{ old('day') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                    <option value="Tuesday" {{ old('day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                    <option value="Wednesday" {{ old('day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                    <option value="Thursday" {{ old('day') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                    <option value="Friday" {{ old('day') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                    <option value="Saturday" {{ old('day') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                    <option value="Sunday" {{ old('day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                </select>
                                @error('day')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="start_time" class="col-md-4 col-form-label text-md-right">Start Time</label>
                            <div class="col-md-6">
                                <input id="start_time" type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="end_time" class="col-md-4 col-form-label text-md-right">End Time</label>
                            <div class="col-md-6">
                                <input id="end_time" type="time" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="is_available" class="col-md-4 col-form-label text-md-right">Availability</label>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">
                                        Available for Appointments
                                    </label>
                                </div>
                                @error('is_available')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Create Schedule
                                </button>
                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
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
@endsection