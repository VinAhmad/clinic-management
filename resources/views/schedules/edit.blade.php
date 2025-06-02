<!-- resources/views/schedules/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Edit Schedule
                        </h2>
                        <p class="text-white/80 mt-2">Update working hours and availability for {{ ucfirst($schedule->day) }}</p>
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
                    <h3 class="text-xl font-bold text-gray-900">Update Schedule Details</h3>
                    <p class="text-gray-600 mt-1">Modify the schedule information below</p>
                </div>

                <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    @if(Auth::user()->role === 'admin')
                        <!-- Doctor Selection -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Doctor <span class="text-red-500">*</span>
                            </label>
                            <select id="doctor_id" name="doctor_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('doctor_id') border-red-500 @enderror" required>
                                <option value="">Choose a doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', $schedule->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }} - {{ $doctor->specialization ?? 'General Practice' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="doctor_id" value="{{ $schedule->doctor_id }}">

                        <!-- Doctor Info Display -->
                        <div class="bg-amber-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-amber-800">Dr. {{ $schedule->doctor->name }}</h4>
                                    <p class="text-amber-600">{{ $schedule->doctor->specialization ?? 'General Practice' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Day Selection -->
                    <div>
                        <label for="day" class="block text-sm font-medium text-gray-700 mb-2">
                            Day of Week <span class="text-red-500">*</span>
                        </label>
                        <select id="day" name="day" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('day') border-red-500 @enderror" required>
                            <option value="">Select a day</option>
                            <option value="monday" {{ old('day', $schedule->day) == 'monday' ? 'selected' : '' }}>Monday</option>
                            <option value="tuesday" {{ old('day', $schedule->day) == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="wednesday" {{ old('day', $schedule->day) == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="thursday" {{ old('day', $schedule->day) == 'thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="friday" {{ old('day', $schedule->day) == 'friday' ? 'selected' : '' }}>Friday</option>
                            <option value="saturday" {{ old('day', $schedule->day) == 'saturday' ? 'selected' : '' }}>Saturday</option>
                            <option value="sunday" {{ old('day', $schedule->day) == 'sunday' ? 'selected' : '' }}>Sunday</option>
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
                            <input id="start_time" type="time" name="start_time" value="{{ old('start_time', $schedule->start_time) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('start_time') border-red-500 @enderror" required>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input id="end_time" type="time" name="end_time" value="{{ old('end_time', $schedule->end_time) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('end_time') border-red-500 @enderror" required>
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
                        <input type="checkbox" id="is_available" name="is_available" value="1" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded" {{ old('is_available', $schedule->is_available) ? 'checked' : '' }}>
                        <label for="is_available" class="text-sm font-medium text-gray-700">
                            Available for appointments
                        </label>
                    </div>

                    <!-- Current Schedule Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Current Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Day:</span>
                                <span class="ml-2 font-medium">{{ ucfirst($schedule->day) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Hours:</span>
                                <span class="ml-2 font-medium">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Status:</span>
                                <span class="ml-2 font-medium {{ $schedule->is_available ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $schedule->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('schedules.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-orange-700 transition duration-200">
                            Update Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>