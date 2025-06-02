<!-- resources/views/schedules/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Create New Schedule
                        </h2>
                        <p class="text-white/80 mt-2">Set up working hours and availability</p>
                    </div>
                    <a href="{{ route('schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Schedules
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Schedule Information</h3>
                    <p class="text-gray-600 mt-1">Configure working hours and availability</p>
                </div>

                <form method="POST" action="{{ route('schedules.store') }}" class="p-6 space-y-6">
                    @csrf

                    @if(Auth::user()->role === 'admin')
                        <!-- Doctor Selection -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Doctor <span class="text-red-500">*</span>
                            </label>
                            <select id="doctor_id" name="doctor_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('doctor_id') border-red-500 @enderror" required>
                                <option value="">Choose a doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', request('doctor_id')) == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }} - {{ $doctor->specialization ?? 'General Practice' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="doctor_id" value="{{ Auth::id() }}">

                        <!-- Doctor Info Display -->
                        <div class="bg-emerald-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-emerald-800">Dr. {{ Auth::user()->name }}</h4>
                                    <p class="text-emerald-600">{{ Auth::user()->specialization ?? 'General Practice' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Day Selection -->
                    <div>
                        <label for="day" class="block text-sm font-medium text-gray-700 mb-2">
                            Day of Week <span class="text-red-500">*</span>
                        </label>
                        <select id="day" name="day" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('day') border-red-500 @enderror" required>
                            <option value="">Select a day</option>
                            <option value="monday" {{ old('day', request('day')) == 'monday' ? 'selected' : '' }}>Monday</option>
                            <option value="tuesday" {{ old('day', request('day')) == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="wednesday" {{ old('day', request('day')) == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="thursday" {{ old('day', request('day')) == 'thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="friday" {{ old('day', request('day')) == 'friday' ? 'selected' : '' }}>Friday</option>
                            <option value="saturday" {{ old('day', request('day')) == 'saturday' ? 'selected' : '' }}>Saturday</option>
                            <option value="sunday" {{ old('day', request('day')) == 'sunday' ? 'selected' : '' }}>Sunday</option>
                        </select>
                        @error('day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Working Hours -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('start_time') border-red-500 @enderror" required>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('end_time') border-red-500 @enderror" required>
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @error('overlap')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ $message }}</p>
                                </div>
                            </div>
                        </div>
                    @enderror

                    <!-- Availability Toggle -->
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" id="is_available" name="is_available" value="1" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded" {{ old('is_available', '1') == '1' ? 'checked' : '' }}>
                        <label for="is_available" class="text-sm font-medium text-gray-700">
                            Available for appointments
                        </label>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Schedule Guidelines</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Ensure end time is after start time</li>
                                        <li>Avoid overlapping with existing schedules</li>
                                        <li>Uncheck "Available" if you don't want to accept appointments during this time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('schedules.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition duration-200">
                            Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
