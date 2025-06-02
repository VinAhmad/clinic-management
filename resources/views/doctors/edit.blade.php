<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Edit Doctor Profile
                        </h2>
                        <p class="text-white/80 mt-2">Update doctor information and credentials</p>
                    </div>
                    <a href="{{ route('doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white font-semibold hover:bg-white/30 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Doctors
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Update Doctor Information</h3>
                    <p class="text-gray-600 mt-1">Modify the doctor's professional details below</p>
                </div>

                <form method="POST" action="{{ route('doctors.update', $doctor) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input id="name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name', $doctor->name) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('name') border-red-500 @enderror"
                                       required
                                       autofocus>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select id="gender"
                                        name="gender"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('gender') border-red-500 @enderror"
                                        required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $doctor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $doctor->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email', $doctor->email) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('email') border-red-500 @enderror"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input id="phone"
                                       type="text"
                                       name="phone"
                                       value="{{ old('phone', $doctor->phone) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('phone') border-red-500 @enderror"
                                       required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address"
                                      name="address"
                                      rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('address') border-red-500 @enderror"
                                      required
                                      placeholder="Enter complete address">{{ old('address', $doctor->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Professional Information Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h4>

                        <!-- Specialization -->
                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">
                                Medical Specialization <span class="text-red-500">*</span>
                            </label>
                            <input id="specialization"
                                   type="text"
                                   name="specialization"
                                   value="{{ old('specialization', $doctor->specialization) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('specialization') border-red-500 @enderror"
                                   required
                                   placeholder="e.g., Cardiology, Pediatrics, General Medicine">
                            @error('specialization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Security Settings</h4>
                        <p class="text-sm text-amber-700 mb-4">Leave password fields blank to keep the current password</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <input id="password"
                                       type="password"
                                       name="password"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('password') border-red-500 @enderror"
                                       autocomplete="new-password"
                                       placeholder="Enter new password">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm New Password
                                </label>
                                <input id="password_confirmation"
                                       type="password"
                                       name="password_confirmation"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('doctors.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition duration-200">
                            Update Doctor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
