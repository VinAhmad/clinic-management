{{-- filepath: c:\University\Database Design\clinic-management\resources\views\auth\register.blade.php --}}
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full" required onchange="toggleSpecializationField()">
                <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>{{ __('Patient') }}</option>
                <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>{{ __('Doctor') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Specialization (Visible only for Doctors) -->
        <div class="mt-4" id="specialization-field" style="display: none;">
            <x-input-label for="specialization" :value="__('Specialization')" />
            <x-text-input id="specialization" class="block mt-1 w-full" type="text" name="specialization" :value="old('specialization')" />
            <x-input-error :messages="$errors->get('specialization')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

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
