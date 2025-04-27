@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>{{ __('Appointment Details') }}</h2>
        </div>
        <div class="col-md-4 text-end">
            @if($appointment->status === 'scheduled')
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> {{ __('Edit Appointment') }}
                </a>
            @endif
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Basic Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">{{ __('Appointment ID:') }}</div>
                        <div class="col-md-8">{{ $appointment->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">{{ __('Date & Time:') }}</div>
                        <div class="col-md-8">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">{{ __('Status:') }}</div>
                        <div class="col-md-8">
                            @if($appointment->status == 'scheduled')
                                <span class="badge bg-primary">{{ __('Scheduled') }}</span>
                            @elseif($appointment->status == 'completed')
                                <span class="badge bg-success">{{ __('Completed') }}</span>
                            @elseif($appointment->status == 'canceled')
                                <span class="badge bg-danger">{{ __('Canceled') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">{{ __('Consultation Fee:') }}</div>
                        <div class="col-md-8">${{ number_format($appointment->fee, 2) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">{{ __('Notes:') }}</div>
                        <div class="col-md-8">{{ $appointment->notes ?? 'No notes provided' }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Patient Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ __('Name:') }}</strong> {{ $appointment->patient->name }}</p>
                            <p><strong>{{ __('Email:') }}</strong> {{ $appointment->patient->email }}</p>
                            @if($appointment->patient->phone)
                                <p><strong>{{ __('Phone:') }}</strong> {{ $appointment->patient->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Doctor Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ __('Name:') }}</strong> Dr. {{ $appointment->doctor->name }}</p>
                            <p><strong>{{ __('Email:') }}</strong> {{ $appointment->doctor->email }}</p>
                            @if($appointment->doctor->specialization)
                                <p><strong>{{ __('Specialization:') }}</strong> {{ $appointment->doctor->specialization }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Payment Information') }}</h5>
                </div>
                <div class="card-body">
                    @if($appointment->payment)
                        <p><strong>{{ __('Payment Status:') }}</strong> 
                            @if($appointment->payment->status == 'pending')
                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                            @elseif($appointment->payment->status == 'paid')
                                <span class="badge bg-success">{{ __('Paid') }}</span>
                            @elseif($appointment->payment->status == 'refunded')
                                <span class="badge bg-info">{{ __('Refunded') }}</span>
                            @elseif($appointment->payment->status == 'failed')
                                <span class="badge bg-danger">{{ __('Failed') }}</span>
                            @endif
                        </p>
                        <p><strong>{{ __('Amount:') }}</strong> ${{ number_format($appointment->payment->amount, 2) }}</p>
                        
                        @if($appointment->payment->payment_method)
                            <p><strong>{{ __('Payment Method:') }}</strong> {{ ucwords(str_replace('_', ' ', $appointment->payment->payment_method)) }}</p>
                        @endif
                        
                        @if($appointment->payment->payment_date)
                            <p><strong>{{ __('Payment Date:') }}</strong> {{ $appointment->payment->payment_date->format('M d, Y') }}</p>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('payments.show', $appointment->payment) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> {{ __('View Payment Details') }}
                            </a>
                            
                            @if($appointment->payment->status == 'pending' && Auth::user()->role == 'patient')
                                <a href="{{ route('payments.process', $appointment->payment) }}" class="btn btn-warning btn-sm mt-2">
                                    <i class="fas fa-credit-card"></i> {{ __('Pay Now') }}
                                </a>
                            @endif
                        </div>
                    @else
                        <p>{{ __('No payment information available.') }}</p>
                    @endif
                </div>
            </div>

            @if(Auth::user()->role == 'doctor' && $appointment->status == 'scheduled')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('medical-records.create', ['appointment' => $appointment->id]) }}" class="btn btn-success mb-2 w-100">
                            <i class="fas fa-notes-medical"></i> {{ __('Create Medical Record') }}
                        </a>
                        
                        <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d') }}">
                            <input type="hidden" name="appointment_time" value="{{ $appointment->appointment_date->format('H:i') }}">
                            <input type="hidden" name="fee" value="{{ $appointment->fee }}">
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-primary mb-2 w-100" onclick="return confirm('Are you sure you want to mark this appointment as completed?')">
                                <i class="fas fa-check"></i> {{ __('Mark as Completed') }}
                            </button>
                        </form>
                        
                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                <i class="fas fa-times"></i> {{ __('Cancel Appointment') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($appointment->medicalRecord)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Medical Record') }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>{{ __('Diagnosis:') }}</strong> {{ Str::limit($appointment->medicalRecord->diagnosis, 100) }}</p>
                        
                        <a href="{{ route('medical-records.show', $appointment->medicalRecord) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> {{ __('View Medical Record') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
