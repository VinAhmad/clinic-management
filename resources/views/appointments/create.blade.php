<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        <!-- Doctor Selection -->
                        <div class="mb-4">
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Patient Selection (only for admin or doctor) -->
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'doctor')
                            <div class="mb-4">
                                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                                <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="patient_id" value="{{ auth()->user()->id }}">
                        @endif

                        <!-- Appointment Date -->
                        <div class="mb-4">
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700">Appointment Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <!-- Appointment Time -->
                        <div class="mb-4">
                            <label for="appointment_time" class="block text-sm font-medium text-gray-700">Appointment Time</label>
                            <input type="time" name="appointment_time" id="appointment_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        <!-- Fee -->
                        <div class="mb-4">
                            <label for="fee" class="block text-sm font-medium text-gray-700">Fee</label>
                            <input type="number" name="fee" id="fee" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
