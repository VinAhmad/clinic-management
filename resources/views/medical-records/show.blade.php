<!-- resources/views/medical-records/show.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Medical Record Details</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">ID:</div>
                        <div class="col-md-8">{{ $medicalRecord->id }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Patient:</div>
                        <div class="col-md-8">{{ $medicalRecord->patient->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Doctor:</div>
                        <div class="col-md-8">{{ $medicalRecord->doctor->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Appointment Date:</div>
                        <div class="col-md-8">{{ $medicalRecord->appointment->appointment_date ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Diagnosis:</div>
                        <div class="col-md-8">{{ $medicalRecord->diagnosis }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Prescription:</div>
                        <div class="col-md-8">{{ $medicalRecord->prescription }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-right font-weight-bold">Treatment Plan:</div>
                        <div class="col-md-8">{{ $medicalRecord->treatment_plan }}</div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-8 offset-md-4">
                            <a href="{{ route('medical-records.edit', $medicalRecord->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection