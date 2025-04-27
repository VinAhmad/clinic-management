<!-- resources/views/schedules/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Schedule') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('schedules.update', $schedule) }}">
                        @csrf
                        @method('PUT')

                        @if(Auth::user()->role === 'admin')
                        <div class="form-group row mb-3">
                            <label for="doctor_id" class="col-md-4 col-form-label text-md-right">{{ __('Doctor') }}</label>

                            <div class="col-md-6">
                                <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id', $schedule->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} ({{ $doctor->specialization ?? 'General Practice' }})
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
                        @else
                        <input type="hidden" name="doctor_id" value="{{ $schedule->doctor_id }}">
                        @endif

                        <div class="form-group row mb-3">
                            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('Day') }}</label>

                            <div class="col-md-6">
                                <select id="day" name="day" class="form-control @error('day') is-invalid @enderror" required>
                                    <option value="monday" {{ old('day', $schedule->day) == 'monday' ? 'selected' : '' }}>Monday</option>
                                    <option value="tuesday" {{ old('day', $schedule->day) == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                    <option value="wednesday" {{ old('day', $schedule->day) == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                    <option value="thursday" {{ old('day', $schedule->day) == 'thursday' ? 'selected' : '' }}>Thursday</option>
                                    <option value="friday" {{ old('day', $schedule->day) == 'friday' ? 'selected' : '' }}>Friday</option>
                                    <option value="saturday" {{ old('day', $schedule->day) == 'saturday' ? 'selected' : '' }}>Saturday</option>
                                    <option value="sunday" {{ old('day', $schedule->day) == 'sunday' ? 'selected' : '' }}>Sunday</option>
                                </select>

                                @error('day')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="start_time" class="col-md-4 col-form-label text-md-right">{{ __('Start Time') }}</label>

                            <div class="col-md-6">
                                <input id="start_time" type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time', $schedule->start_time) }}" required>

                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="end_time" class="col-md-4 col-form-label text-md-right">{{ __('End Time') }}</label>

                            <div class="col-md-6">
                                <input id="end_time" type="time" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time', $schedule->end_time) }}" required>

                                @error('end_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                @error('overlap')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $schedule->is_available) ? 'checked' : '' }}>

                                    <label class="form-check-label" for="is_available">
                                        {{ __('Available for appointments') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Schedule') }}
                                </button>
                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
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