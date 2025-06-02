<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Appointment Details
                        </h2>
                        <p class="text-white/80 mt-2">View appointment information and related records</p>
                    </div>
                    <div class="flex space-x-3">
                        @if($appointment->status === 'scheduled')
                            <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Appointment
                            </a>
                        @endif
                        <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to List
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Appointment Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    @if($appointment->status == 'scheduled')
                                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    @elseif($appointment->status == 'completed')
                                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Appointment #{{ $appointment->id }}</h3>
                                    <p class="text-gray-600">{{ $appointment->appointment_date->format('l, F j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Patient Information</h4>
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-blue-600 font-semibold text-sm">{{ substr($appointment->patient->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $appointment->patient->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->patient->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Doctor Information</h4>
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Dr. {{ $appointment->doctor->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->doctor->specialization ?? 'General Practitioner' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Appointment Status</h4>
                                    @if($appointment->status == 'scheduled')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            Scheduled
                                        </span>
                                    @elseif($appointment->status == 'completed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @elseif($appointment->status == 'canceled')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            Canceled
                                        </span>
                                    @endif
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Consultation Fee</h4>
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($appointment->fee, 2) }}</p>
                                </div>
                            </div>

                            @if($appointment->notes)
                                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Notes</h4>
                                    <p class="text-gray-700">{{ $appointment->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Information -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                                Payment Information
                            </h3>
                        </div>

                        <div class="p-6">
                            @if($appointment->payment)
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Amount:</span>
                                        <span class="font-semibold text-gray-900">${{ number_format($appointment->payment->amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        @if($appointment->payment->status == 'paid')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                        @elseif($appointment->payment->status == 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ ucfirst($appointment->payment->status) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($appointment->payment->payment_method)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Method:</span>
                                            <span class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $appointment->payment->payment_method)) }}</span>
                                        </div>
                                    @endif
                                    @if($appointment->payment->payment_date)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Paid On:</span>
                                            <span class="font-medium text-gray-900">{{ $appointment->payment->payment_date->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                    <div class="pt-4">
                                        <a href="{{ route('payments.show', $appointment->payment) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                                            View Payment Details
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-2">No Payment Record</h4>
                                    <p class="text-gray-600 text-sm">Payment information will appear here once processed.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Doctor Actions -->
                    @if(Auth::user()->role == 'doctor' && $appointment->status == 'scheduled')
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <a href="{{ route('medical-records.create', ['appointment' => $appointment->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h4a2 2 0 002-2V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    Add Medical Record
                                </a>

                                <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Mark as Completed
                                    </button>
                                </form>

                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Cancel Appointment
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Medical Record -->
                    @if($appointment->medicalRecord)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h4a2 2 0 002-2V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    Medical Record
                                </h3>
                            </div>

                            <div class="p-6">
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-gray-600 text-sm">Diagnosis:</span>
                                        <p class="font-medium text-gray-900">{{ Str::limit($appointment->medicalRecord->diagnosis, 100) }}</p>
                                    </div>
                                    @if($appointment->medicalRecord->symptoms)
                                        <div>
                                            <span class="text-gray-600 text-sm">Symptoms:</span>
                                            <p class="text-gray-700">{{ Str::limit($appointment->medicalRecord->symptoms, 80) }}</p>
                                        </div>
                                    @endif
                                    <div class="pt-4">
                                        <a href="{{ route('medical-records.show', $appointment->medicalRecord) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition">
                                            View Full Record
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
