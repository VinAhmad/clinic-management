<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 -mx-4 -mt-4 px-4 pt-8 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            Add New Doctor
                        </h2>
                        <p class="text-white/80 mt-2">Register a new medical practitioner in the system</p>
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
                    <h3 class="text-xl font-bold text-gray-900">Doctor Information</h3>
                    <p class="text-gray-600 mt-1">Fill in the details below to register a new doctor</p>
                </div>

                <form method="POST" action="{{ route('doctors.store') }}" class="p-6 space-y-6">
                    @csrf

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
                                       value="{{ old('name') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror"
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
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('gender') border-red-500 @enderror"
                                        required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
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
                                       value="{{ old('email') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror"
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
                                       value="{{ old('phone') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('phone') border-red-500 @enderror"
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
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('address') border-red-500 @enderror"
                                      required
                                      placeholder="Enter complete address">{{ old('address') }}</textarea>
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
                                   value="{{ old('specialization') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('specialization') border-red-500 @enderror"
                                   required
                                   placeholder="e.g., Cardiology, Pediatrics, General Medicine">
                            @error('specialization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Account Credentials Section -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Account Credentials</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password"
                                       type="password"
                                       name="password"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror"
                                       required
                                       autocomplete="new-password">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password_confirmation"
                                       type="password"
                                       name="password_confirmation"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Reset
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition duration-200">
                            Create Doctor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
