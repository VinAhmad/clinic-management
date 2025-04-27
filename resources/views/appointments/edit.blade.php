{{-- resources/views/appointments/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Doctor --}}
                        <div class="mb-4">
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor</label>
                            <select name="doctor_id" id="doctor_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Patient (admin/doctor only) --}}
                        @if(in_array(auth()->user()->role, ['admin','doctor']))
                            <div class="mb-4">
                                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                                <select name="patient_id" id="patient_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="patient_id" value="{{ auth()->id() }}">
                        @endif

                        {{-- Date --}}
                        <div class="mb-4">
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" required
                                value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Time --}}
                        <div class="mb-4">
                            <label for="appointment_time" class="block text-sm font-medium text-gray-700">Time</label>
                            <input type="time" name="appointment_time" id="appointment_time" required
                                value="{{ old('appointment_time', $appointment->appointment_date->format('H:i')) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $appointment->notes) }}</textarea>
                        </div>

                        {{-- Fee --}}
                        <div class="mb-4">
                            <label for="fee" class="block text-sm font-medium text-gray-700">Fee</label>
                            <input type="number" name="fee" id="fee" step="0.01" min="0" required
                                value="{{ old('fee', $appointment->fee) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Status (admin/doctor only) --}}
                        @if(auth()->user()->role !== 'patient')
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach(['scheduled','completed','canceled'] as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $appointment->status) == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Submit --}}
                        <div class="mt-6">
                            <button type="submit"
                                class="inline-block rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 transition">
                                Update Appointment
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
