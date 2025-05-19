<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doctor Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Dr. {{ $doctor->name }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $doctor->specialization ?? 'General Practice' }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('schedules.doctor', $doctor) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        View Schedule
                    </a>
                    <a href="{{ route('doctors.edit', $doctor) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                        Edit
                    </a>
                    <a href="{{ route('doctors.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-800 hover:bg-gray-300">
                        Back to List
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Personal Information</h4>
                            <div class="mb-4">
                                <div class="text-sm font-medium text-gray-500">Email</div>
                                <div>{{ $doctor->email }}</div>
                            </div>
                            <div class="mb-4">
                                <div class="text-sm font-medium text-gray-500">Phone</div>
                                <div>{{ $doctor->phone }}</div>
                            </div>
                            <div class="mb-4">
                                <div class="text-sm font-medium text-gray-500">Gender</div>
                                <div>{{ ucfirst($doctor->gender) }}</div>
                            </div>
                            <div class="mb-4">
                                <div class="text-sm font-medium text-gray-500">Address</div>
                                <div>{{ $doctor->address }}</div>
                            </div>
                            <div class="mb-4">
                                <div class="text-sm font-medium text-gray-500">Registered Since</div>
                                <div>{{ $doctor->created_at->format('F d, Y') }}</div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Recent Appointments</h4>
                            @if($appointments->isEmpty())
                                <div class="text-sm text-gray-500">No recent appointments found.</div>
                            @else
                                <div class="space-y-3">
                                    @foreach($appointments as $appointment)
                                        <div class="border rounded-md p-3">
                                            <div class="flex justify-between">
                                                <div class="font-medium">{{ $appointment->patient->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</div>
                                            </div>
                                            <div class="text-sm mt-1">
                                                <span class="px-2 py-1 rounded-full text-xs {{ $appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-800 mb-3">Weekly Schedule</h4>
                        @if($schedules->isEmpty())
                            <div class="text-sm text-gray-500">No schedule set up yet.</div>
                            <div class="mt-2">
                                <a href="{{ route('schedules.create') }}?doctor_id={{ $doctor->id }}" class="text-indigo-600 hover:text-indigo-800">
                                    <span class="underline">Set up schedule</span>
                                </a>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Day
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Hours
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($schedules as $schedule)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ ucfirst($schedule->day) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ date('h:i A', strtotime($schedule->start_time)) }} - {{ date('h:i A', strtotime($schedule->end_time)) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $schedule->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $schedule->is_available ? 'Available' : 'Not Available' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
