<!-- resources/views/schedules/index.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Doctor Schedules</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Schedule
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Doctor</th>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->id }}</td>
                                <td>{{ $schedule->doctor->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->day }}</td>
                                <td>{{ date('h:i A', strtotime($schedule->start_time)) }}</td>
                                <td>{{ date('h:i A', strtotime($schedule->end_time)) }}</td>
                                <td>
                                    @if($schedule->is_available)
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-danger">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this schedule?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No schedules found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection