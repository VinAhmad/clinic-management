<!-- resources/views/medical-records/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Medical Records</h3>
                        <a href="{{ route('medical-records.create') }}" class="btn btn-primary">Create New Record</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <form action="{{ route('medical-records.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <select name="patient_id" class="form-control">
                                    <option value="">Filter by Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="doctor_id" class="form-control">
                                    <option value="">Filter by Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                                <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Appointment</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($medicalRecords as $record)
                                <tr>
                                    <td>{{ $record->id }}</td>
                                    <td>{{ $record->appointment_id }}</td>
                                    <td>{{ $record->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $record->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                                    <td>
                                        <a href="{{ route('medical-records.edit', $record->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewRecord{{ $record->id }}">
                                            View
                                        </button>
                                        <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewRecord{{ $record->id }}" tabindex="-1" aria-labelledby="viewRecordLabel{{ $record->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewRecordLabel{{ $record->id }}">Medical Record Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6>Diagnosis</h6>
                                                <p>{{ $record->diagnosis }}</p>
                                                
                                                <h6>Prescription</h6>
                                                <p>{{ $record->prescription }}</p>
                                                
                                                <h6>Treatment Plan</h6>
                                                <p>{{ $record->treatment_plan }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No medical records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-3">
                        {{ $medicalRecords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection