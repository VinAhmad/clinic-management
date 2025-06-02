<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Doctor Profile
                        </h2>
                        <p class="text-white/80 mt-2">Detailed information and schedule overview</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('schedules.doctor', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            View Schedule
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('doctors.edit', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Profile
                            </a>
                        @endif
                        <a href="{{ route('doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to Doctors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Doctor Profile Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Dr. {{ $doctor->name }}</h3>
                            <p class="text-gray-600 mt-1">{{ $doctor->specialization ?? 'General Practice' }}</p>
                            <div class="flex items-center mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>
                                <span class="ml-3 text-sm text-gray-500">Member since {{ $doctor->created_at->format('F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Contact Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Email Address</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ $doctor->email }}</p>
                                </div>
                                @if($doctor->phone)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="text-sm font-medium text-gray-500">Phone Number</label>
                                        <p class="text-sm font-semibold text-gray-900">{{ $doctor->phone }}</p>
                                    </div>
                                @endif
                                @if($doctor->gender)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="text-sm font-medium text-gray-500">Gender</label>
                                        <p class="text-sm font-semibold text-gray-900">{{ ucfirst($doctor->gender) }}</p>
                                    </div>
                                @endif
                                @if($doctor->address)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="text-sm font-medium text-gray-500">Address</label>
                                        <p class="text-sm font-semibold text-gray-900">{{ $doctor->address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Professional Details</h4>
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Specialization</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ $doctor->specialization ?? 'General Practice' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Registration Date</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ $doctor->created_at->format('F j, Y') }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Total Appointments</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ $appointments ? $appointments->count() : 0 }} appointments</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-sm font-medium text-gray-500">Account Status</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Appointments -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-bold text-gray-900">Recent Appointments</h4>
                            <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
                        </div>
                    </div>

                    <div class="p-6">
                        @if(!$appointments || $appointments->isEmpty())
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">No appointments yet</h4>
                                <p class="text-gray-500">Appointments will appear here when scheduled</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($appointments->take(5) as $appointment)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                    <div class="flex justify-between items-start">
                                        <div class="flex">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-gray-900">{{ $appointment->patient->name }}</h5>
                                                <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</p>
                                                @if($appointment->notes)
                                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($appointment->notes, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            @if($appointment->status == 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    Completed
                                                </span>
                                            @elseif($appointment->status == 'scheduled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                    Scheduled
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            @endif
                                            <a href="{{ route('appointments.show', $appointment) }}" class="text-xs text-blue-600 hover:text-blue-800 mt-1">View Details</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($appointments->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all {{ $appointments->count() }} appointments</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Weekly Schedule -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-bold text-gray-900">Weekly Schedule</h4>
                            <a href="{{ route('schedules.doctor', $doctor) }}" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">Manage Schedule</a>
                        </div>
                    </div>

                    <div class="p-6">
                        @if(!$schedules || $schedules->isEmpty())
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">No schedule set up</h4>
                                <p class="text-gray-500 mb-4">Doctor hasn't configured their working schedule yet</p>
                                @if(Auth::user()->role === 'admin' || Auth::user()->id === $doctor->id)
                                    <a href="{{ route('schedules.create') }}?doctor_id={{ $doctor->id }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-green-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        Set Up Schedule
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($schedules as $schedule)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                            <span class="text-emerald-600 font-semibold text-xs">{{ substr(ucfirst($schedule->day), 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ ucfirst($schedule->day) }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($schedule->is_available)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Available
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                                Not Available
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
