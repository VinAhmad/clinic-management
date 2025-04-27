@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>{{ __('Doctor Schedules Management') }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Create New Schedule') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($doctors->isEmpty())
                <div class="alert alert-info">
                    {{ __('No doctors found.') }}
                </div>
            @else
                <div class="list-group">
                    @foreach($doctors as $doctor)
                        <a href="{{ route('schedules.doctor', $doctor) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Dr. {{ $doctor->name }}</h5>
                                <p class="mb-1 text-muted">{{ $doctor->specialization ?? 'General Practice' }}</p>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                {{ $doctor->schedules_count ?? $doctor->schedules()->count() }} schedules
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
