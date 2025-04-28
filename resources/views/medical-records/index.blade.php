<!-- resources/views/medical-records/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2>{{ __('Medical Records') }}</h2>
        </div>
        @if(Auth::user()->role !== 'patient')
        <div class="col-md-4 text-end">
            <a href="{{ route('medical-records.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('New Medical Record') }}
            </a>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            @if($medicalRecords->isEmpty())
                <div class="alert alert-info">
                    {{ __('No medical records found.') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Patient') }}</th>
                                <th>{{ __('Doctor') }}</th>
                                <th>{{ __('Diagnosis') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicalRecords as $index => $record)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $record->created_at->format('M d, Y') }}</td>
                                    <td>{{ $record->patient->name }}</td>
                                    <td>Dr. {{ $record->doctor->name }}</td>
                                    <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('medical-records.show', $record) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i> View
                                            </a>

                                            @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'doctor' && Auth::id() === $record->doctor_id))
                                                <a href="{{ route('medical-records.edit', $record) }}" class="btn btn-sm btn-primary ms-1" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @endif

                                            @if(Auth::user()->role === 'admin')
                                                <form action="{{ route('medical-records.destroy', $record) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $medicalRecords->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
