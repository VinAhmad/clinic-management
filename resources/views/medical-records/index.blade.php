<!-- resources/views/medical-records/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Medical Records
                        </h2>
                        <p class="text-white/80 mt-2">Manage patient health records and history</p>
                    </div>
                    @if(Auth::user()->role !== 'patient')
                        <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            New Medical Record
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">
                            @if(Auth::user()->role === 'patient')
                                My Medical Records
                            @elseif(Auth::user()->role === 'doctor')
                                Patient Medical Records
                            @else
                                All Medical Records
                            @endif
                        </h3>
                        <div class="text-sm text-gray-600">
                            Total: {{ $medicalRecords->total() }} records
                        </div>
                    </div>
                </div>

                @if($medicalRecords->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    @if(Auth::user()->role !== 'patient')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    @endif
                                    @if(Auth::user()->role !== 'doctor')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($medicalRecords as $index => $record)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ($medicalRecords->currentPage() - 1) * $medicalRecords->perPage() + $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h4a2 2 0 002-2V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $record->created_at->format('M d, Y') }}</div>
                                                    <div class="text-sm text-gray-500">{{ $record->created_at->format('h:i A') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        @if(Auth::user()->role !== 'patient')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                        <span class="text-blue-600 font-semibold text-xs">{{ substr($record->patient->name, 0, 1) }}</span>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $record->patient->name }}</div>
                                                </div>
                                            </td>
                                        @endif
                                        @if(Auth::user()->role !== 'doctor')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">Dr. {{ $record->doctor->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $record->doctor->specialization ?? 'General Practitioner' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ Str::limit($record->diagnosis, 60) }}</div>
                                            @if($record->symptoms)
                                                <div class="text-sm text-gray-500 mt-1">Symptoms: {{ Str::limit($record->symptoms, 40) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('medical-records.show', $record) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-200 transition">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    View
                                                </a>

                                                @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'doctor' && Auth::id() === $record->doctor_id))
                                                    <a href="{{ route('medical-records.edit', $record) }}" class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-medium hover:bg-amber-200 transition">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endif

                                                @if(Auth::user()->role === 'admin')
                                                    <form action="{{ route('medical-records.destroy', $record) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            Delete
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

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $medicalRecords->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h4a2 2 0 002-2V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No medical records found</h4>
                        <p class="text-gray-500 mb-4">
                            @if(Auth::user()->role === 'patient')
                                You don't have any medical records yet.
                            @else
                                No medical records have been created yet.
                            @endif
                        </p>
                        @if(Auth::user()->role !== 'patient')
                            <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg shadow-md hover:from-teal-700 hover:to-cyan-700 transform transition duration-200 hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Create First Medical Record
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
