<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doctor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Patients Seen</div>
                        <div class="text-3xl font-bold">{{ $totalPatients }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Appointments</div>
                        <div class="text-3xl font-bold">{{ $totalAppointments }}</div>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Schedule</h3>

                    @if(isset($todaySchedule) && $todaySchedule->id)
                        <div class="bg-blue-50 p-4 mb-4 rounded">
                            <p class="text-blue-800">Working Hours:
                                <span class="font-semibold">{{ Carbon\Carbon::parse($todaySchedule->start_time)->format('h:i A') }} -
                                {{ Carbon\Carbon::parse($todaySchedule->end_time)->format('h:i A') }}</span>
                            </p>
                        </div>
                    @else
                        <div class="bg-yellow-50 p-4 rounded text-center">
                            <p class="text-yellow-800 mb-2">No schedule set for today ({{ now()->format('l') }}).</p>
                            <a href="{{ route('schedules.create') }}" class="text-indigo-600 hover:text-indigo-800">
                                <span class="underline">Set up your schedule</span>
                            </a>
                        </div>
                    @endif

                    <h4 class="font-medium text-gray-700 mt-6 mb-2">Today's Appointments</h4>
                    @if($todayAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($todayAppointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_date->format('h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->patient->name }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ $appointment->notes ?? 'No notes' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('appointments.show', $appointment) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                            @if($appointment->status === 'scheduled')
                                                <a href="{{ route('medical-records.create', ['appointment' => $appointment->id]) }}" class="text-green-600 hover:text-green-900">Add Medical Record</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded text-center">
                            No appointments scheduled for today.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Weekly Schedule -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Weekly Schedule</h3>
                        <a href="{{ route('schedules.index') }}" class="text-indigo-600 hover:text-indigo-800">Manage Schedules</a>
                    </div>

                    @if($weeklySchedules->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($weeklySchedules as $schedule)
                                    <tr class="{{ strtolower(now()->format('l')) === $schedule->day ? 'bg-blue-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($schedule->day) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
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
                    @else
                        <div class="bg-yellow-50 p-4 rounded text-center">
                            <p class="text-yellow-800 mb-2">You haven't set up your weekly schedule yet.</p>
                            <a href="{{ route('schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Set Up Schedule
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Upcoming Appointments</h3>
                        <a href="{{ route('appointments.index') }}" class="text-indigo-600 hover:text-indigo-800">View All</a>
                    </div>

                    @if($upcomingAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($upcomingAppointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_date->format('h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->patient->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('appointments.show', $appointment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded text-center">
                            No upcoming appointments.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Manage Schedule</h3>
                        <div class="space-y-2">
                            <a href="{{ route('schedules.index') }}" class="block text-indigo-600 hover:text-indigo-800">View My Schedule</a>
                            <a href="{{ route('schedules.create') }}" class="block text-indigo-600 hover:text-indigo-800">Add Schedule Slot</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Medical Records</h3>
                        <div class="space-y-2">
                            <a href="{{ route('medical-records.index') }}" class="block text-indigo-600 hover:text-indigo-800">View Medical Records</a>
                            <a href="{{ route('medical-records.create') }}" class="block text-indigo-600 hover:text-indigo-800">Create New Record</a>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Payments</h3>
                        <div class="space-y-2">
                            <a href="{{ route('payments.index') }}" class="block text-indigo-600 hover:text-indigo-800">View Payments</a>
                            <a href="{{ route('payments.reports') }}" class="block text-indigo-600 hover:text-indigo-800">Payment Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
