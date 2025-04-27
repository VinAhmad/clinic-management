<!-- resources/views/appointments/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>{{ __('Appointments') }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('New Appointment') }}
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
            @if($appointments->isEmpty())
                <div class="alert alert-info">
                    {{ __('No appointments found.') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Date & Time') }}</th>
                                <th>{{ __('Patient') }}</th>
                                <th>{{ __('Doctor') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Fee') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->id }}</td>
                                    <td>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>Dr. {{ $appointment->doctor->name }}</td>
                                    <td>
                                        @if($appointment->status == 'scheduled')
                                            <span class="badge bg-primary">{{ __('Scheduled') }}</span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="badge bg-success">{{ __('Completed') }}</span>
                                        @elseif($appointment->status == 'canceled')
                                            <span class="badge bg-danger">{{ __('Canceled') }}</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($appointment->fee, 2) }}</td>
                                    <td>
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        @if($appointment->status == 'scheduled')
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            
                                            @if(Auth::user()->role == 'doctor')
                                                <a href="{{ route('medical-records.create', ['appointment' => $appointment->id]) }}" class="btn btn-sm btn-success" title="Add Medical Record">
                                                    <i class="fas fa-notes-medical"></i> Record
                                                </a>
                                            @endif
                                            
                                            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Cancel">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection