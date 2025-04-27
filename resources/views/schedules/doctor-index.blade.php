@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>{{ __('My Schedule') }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Add Schedule') }}
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
            @if($schedules->isEmpty())
                <div class="alert alert-info">
                    {{ __('You have not created any schedules yet.') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Day') }}</th>
                                <th>{{ __('Hours') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                @php
                                    $daySchedule = $schedules->where('day', $day)->first();
                                @endphp
                                <tr>
                                    <td>{{ ucfirst($day) }}</td>
                                    @if($daySchedule)
                                        <td>{{ date('h:i A', strtotime($daySchedule->start_time)) }} - {{ date('h:i A', strtotime($daySchedule->end_time)) }}</td>
                                        <td>
                                            @if($daySchedule->is_available)
                                                <span class="badge bg-success">{{ __('Available') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Unavailable') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('schedules.edit', $daySchedule) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('schedules.destroy', $daySchedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @else
                                        <td colspan="3" class="text-center">
                                            <a href="{{ route('schedules.create') }}?day={{ $day }}" class="btn btn-sm btn-outline-primary">
                                                {{ __('Add schedule for this day') }}
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
