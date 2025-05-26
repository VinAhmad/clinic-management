{{-- filepath: c:\University\Database Design\clinic-management\resources\views\auth\register.blade.php --}}
<x-guest-layout>
    <div class="flex w-full min-h-screen">
        <!-- Left Panel - Welcome Section -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
                <div class="mb-8">
                    <x-application-logo class="w-24 h-24 fill-current text-white" />
                </div>
                <h1 class="text-4xl font-bold mb-6 text-center">Join Our Clinic</h1>
                <p class="text-xl text-center leading-relaxed opacity-90 max-w-md">
                    Create your account to start managing your healthcare journey or practice with our comprehensive platform.
                </p>
                <div class="mt-12 flex space-x-4 opacity-20">
                    <div class="w-20 h-20 bg-white/20 rounded-lg"></div>
                    <div class="w-20 h-20 bg-white/20 rounded-lg"></div>
                    <div class="w-20 h-20 bg-white/20 rounded-lg"></div>
                </div>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full"></div>
            <div class="absolute bottom-10 left-10 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>

        <!-- Right Panel - Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <x-application-logo class="w-16 h-16 fill-current text-emerald-600" />
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                        <p class="text-gray-600 mt-2">Fill in your details to get started</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 font-medium" />
                            <x-text-input id="name"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                type="text"
                                name="name"
                                :value="old('name')"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Enter your full name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
                            <x-text-input id="email"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autocomplete="username"
                                placeholder="Enter your email address" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div>
                            <x-input-label for="role" :value="__('Account Type')" class="text-gray-700 font-medium" />
                            <select id="role"
                                name="role"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                required
                                onchange="toggleSpecializationField()">
                                <option value="">Select your role</option>
                                <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>{{ __('Patient') }}</option>
                                <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>{{ __('Doctor') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Specialization (Visible only for Doctors) -->
                        <div id="specialization-field" style="display: none;">
                            <x-input-label for="specialization" :value="__('Medical Specialization')" class="text-gray-700 font-medium" />
                            <x-text-input id="specialization"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                type="text"
                                name="specialization"
                                :value="old('specialization')"
                                placeholder="e.g., Cardiology, Pediatrics, etc." />
                            <x-input-error :messages="$errors->get('specialization')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                            <x-text-input id="password"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Create a strong password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium" />
                            <x-text-input id="password_confirmation"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirm your password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <x-primary-button class="w-full justify-center py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg transform transition duration-200 hover:scale-[1.02]">
                            {{ __('Create Account') }}
                        </x-primary-button>

                        <div class="text-center">
                            <span class="text-gray-600">Already have an account?</span>
                            <a class="text-emerald-600 hover:text-emerald-500 font-medium ml-1" href="{{ route('login') }}">
                                {{ __('Sign in here') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSpecializationField() {
            const role = document.getElementById('role').value;
            const specializationField = document.getElementById('specialization-field');
            if (role === 'doctor') {
                specializationField.style.display = 'block';
            } else {
                specializationField.style.display = 'none';
            }
        }

        // Initialize the field visibility based on old input
        document.addEventListener('DOMContentLoaded', function () {
            toggleSpecializationField();
        });
    </script>
</x-guest-layout>
