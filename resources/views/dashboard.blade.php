<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ __("You're logged in!") }}</span>
                    </div>

                    <div class="mb-6">
                        <p class="mb-4">Welcome to the Clinic Management System.</p>
                        <p class="mb-4">You will be redirected to your {{ Auth::user()->role }} dashboard in a moment.</p>
                    </div>

                    <div class="flex items-center mb-6">
                        <div class="mr-3">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div id="countdown">Loading your dashboard in <span id="timer">3</span> seconds...</div>
                    </div>

                    <div class="mt-4">
                        <div class="relative">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full w-0 transition-all duration-1000"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Countdown and progress bar
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 3; // Changed from 5 to 3
            const timerElement = document.getElementById('timer');
            const progressBar = document.getElementById('progress-bar');

            // Initial progress
            progressBar.style.width = '0%';

            // Update every second
            const interval = setInterval(function() {
                timeLeft--;
                timerElement.textContent = timeLeft;

                // Update progress bar
                const progress = (3 - timeLeft) * 33.33; // Changed from 20 to 33.33 (100/3)
                progressBar.style.width = progress + '%';

                if (timeLeft <= 0) {
                    clearInterval(interval);
                    window.location.href = "{{ route('dashboard.redirect') }}";
                }
            }, 1000);
        });
    </script>
</x-app-layout>
