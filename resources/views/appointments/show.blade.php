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
            <div class="card mb-4 border-0 shadow rounded-3 overflow-hidden">
                <div class="card-header bg-purple bg-gradient text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        {{ __('Basic Information') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light p-3 rounded-circle me-3">
                            @if($appointment->status == 'scheduled')
                                <i class="fas fa-calendar-day fa-2x text-primary"></i>
                            @elseif($appointment->status == 'completed')
                                <i class="fas fa-calendar-check fa-2x text-success"></i>
                            @else
                                <i class="fas fa-calendar-times fa-2x text-danger"></i>
                            @endif
                        </div>
                        <div>
                            <h5 class="mb-0">Appointment -{{ $appointment->id }}</h5>
                            <p class="text-muted mb-0">
                                Created on {{ $appointment->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body py-3">
                                    <div class="small text-muted mb-1">Date & Time</div>
                                    <div class="fs-5">
                                        <i class="far fa-clock me-1 text-primary"></i>
                                        {{ $appointment->appointment_date->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body py-3">
                                    <div class="small text-muted mb-1">Status</div>
                                    <div>
                                        @if($appointment->status == 'scheduled')
                                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ __('Scheduled') }}</span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6">{{ __('Completed') }}</span>
                                        @elseif($appointment->status == 'canceled')
                                            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">{{ __('Canceled') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body py-3">
                                    <div class="small text-muted mb-1">Consultation Fee</div>
                                    <div class="fs-4 text-success fw-semibold">
                                        <i class="fas fa-tag me-1"></i>
                                        ${{ number_format($appointment->fee, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body py-3">
                                    <div class="small text-muted mb-1">Notes</div>
                                    <div class="p-3 bg-white rounded border">
                                        {{ $appointment->notes ?? 'No notes provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow rounded-3 overflow-hidden">
                        <div class="card-header bg-primary bg-gradient text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user-injured me-2"></i>
                                {{ __('Patient Information') }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light p-3 rounded-circle me-3">
                                    <i class="fas fa-user fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $appointment->patient->name }}</h5>
                                    <p class="text-muted mb-0 small">Patient ID: {{ $appointment->patient->id }}</p>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row mt-3">
                                <div class="col-md-12 mb-2">
                                    <div class="d-flex">
                                        <span class="text-muted me-2 w-25">Email:</span>
                                        <span class="fw-medium">{{ $appointment->patient->email }}</span>
                                    </div>
                                </div>
                                @if($appointment->patient->phone)
                                <div class="col-md-12 mb-2">
                                    <div class="d-flex">
                                        <span class="text-muted me-2 w-25">Phone:</span>
                                        <span class="fw-medium">{{ $appointment->patient->phone }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow rounded-3 overflow-hidden">
                        <div class="card-header bg-info bg-gradient text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user-md me-2"></i>
                                {{ __('Doctor Information') }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light p-3 rounded-circle me-3">
                                    <i class="fas fa-stethoscope fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Dr. {{ $appointment->doctor->name }}</h5>
                                    @if($appointment->doctor->specialization)
                                        <p class="text-muted mb-0 small">{{ $appointment->doctor->specialization }}</p>
                                    @endif
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row mt-3">
                                <div class="col-md-12 mb-2">
                                    <div class="d-flex">
                                        <span class="text-muted me-2 w-25">Email:</span>
                                        <span class="fw-medium">{{ $appointment->doctor->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 border-0 shadow rounded-3 overflow-hidden">
                <div class="card-header bg-success bg-gradient text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        {{ __('Payment Information') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($appointment->payment)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light p-3 rounded-circle me-3">
                                @if($appointment->payment->status == 'paid')
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                @elseif($appointment->payment->status == 'pending')
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                @elseif($appointment->payment->status == 'refunded')
                                    <i class="fas fa-undo fa-2x text-info"></i>
                                @else
                                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-0">${{ number_format($appointment->payment->amount, 2) }}</h5>
                                <p class="mb-0">
                                    @if($appointment->payment->status == 'pending')
                                        <span class="badge bg-warning rounded-pill px-3">{{ __('Pending') }}</span>
                                    @elseif($appointment->payment->status == 'paid')
                                        <span class="badge bg-success rounded-pill px-3">{{ __('Paid') }}</span>
                                    @elseif($appointment->payment->status == 'refunded')
                                        <span class="badge bg-info rounded-pill px-3">{{ __('Refunded') }}</span>
                                    @elseif($appointment->payment->status == 'failed')
                                        <span class="badge bg-danger rounded-pill px-3">{{ __('Failed') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row">
                            @if($appointment->payment->payment_method)
                            <div class="col-md-12 mb-2">
                                <div class="d-flex">
                                    <span class="text-muted me-2 w-50">{{ __('Payment Method:') }}</span>
                                    <span class="fw-medium">{{ ucwords(str_replace('_', ' ', $appointment->payment->payment_method)) }}</span>
                                </div>
                            </div>
                            @endif

                            @if($appointment->payment->payment_date)
                            <div class="col-md-12 mb-2">
                                <div class="d-flex">
                                    <span class="text-muted me-2 w-50">{{ __('Payment Date:') }}</span>
                                    <span class="fw-medium">{{ $appointment->payment->payment_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('payments.show', $appointment->payment) }}" class="btn btn-info w-100 mb-2">
                                <i class="fas fa-eye"></i> {{ __('View Payment Details') }}
                            </a>

                            @if($appointment->payment->status == 'pending' && Auth::user()->role == 'patient')
                                <a href="{{ route('payments.process', $appointment->payment) }}" class="btn btn-warning w-100">
                                    <i class="fas fa-credit-card"></i> {{ __('Pay Now') }}
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                            <p class="mb-0">{{ __('No payment information available.') }}</p>
                        </div>
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
                <div class="card mb-4 border-0 shadow rounded-3 overflow-hidden">
                    <div class="card-header bg-warning bg-gradient text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-notes-medical me-2"></i>
                            {{ __('Medical Record') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light p-3 rounded-circle me-3">
                                <i class="fas fa-file-medical fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Record -{{ $appointment->medicalRecord->id }}</h5>
                                <p class="text-muted mb-0 small">{{ $appointment->medicalRecord->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3">
                            <h6 class="text-muted mb-2">{{ __('Diagnosis:') }}</h6>
                            <p class="p-3 bg-light rounded border mb-0">
                                {{ Str::limit($appointment->medicalRecord->diagnosis, 150) }}
                                @if(strlen($appointment->medicalRecord->diagnosis) > 150)
                                    <span class="text-muted">...</span>
                                @endif
                            </p>
                        </div>

                        <a href="{{ route('medical-records.show', $appointment->medicalRecord) }}" class="btn btn-warning w-100 mt-3">
                            <i class="fas fa-eye"></i> {{ __('View Complete Medical Record') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
