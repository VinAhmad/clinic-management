<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute top-20 left-20 w-40 h-40 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute top-40 right-32 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute bottom-32 left-40 w-48 h-48 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute bottom-20 right-20 w-36 h-36 bg-white/10 rounded-full blur-xl"></div>

        <!-- Main content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <!-- Logo and brand -->
                <div class="text-center mb-12">
                    <div class="mb-6">
                        <x-application-logo class="w-20 h-20 fill-current text-white mx-auto" />
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-4">Clinic Management</h1>
                    <p class="text-white/80 text-lg">Welcome to your healthcare dashboard</p>
                </div>

                <!-- Status card -->
                <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-8">
                    <!-- Success indicator -->
                    <div class="flex items-center justify-center mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Successfully Logged In!</h2>
                        <p class="text-gray-600 mb-4">Welcome back, {{ Auth::user()->name }}!</p>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            {{ ucfirst(Auth::user()->role) }} Account
                        </div>
                    </div>

                    <!-- Loading section -->
                    <div class="text-center mb-6">
                        <div class="flex items-center justify-center mb-4">
                            <svg class="animate-spin h-6 w-6 text-indigo-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">Preparing your dashboard...</span>
                        </div>

                        <div id="countdown" class="text-gray-600 mb-4">
                            Redirecting to your {{ Auth::user()->role }} dashboard in <span id="timer" class="font-bold text-indigo-600">3</span> seconds
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="relative">
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div id="progress-bar" class="bg-gradient-to-r from-indigo-500 to-purple-500 h-3 rounded-full w-0 transition-all duration-1000 ease-out"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span id="progress-text" class="text-sm text-gray-500">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Quick info -->
                <div class="text-center text-white/70">
                    <p class="text-sm">You're being redirected to access your personalized dashboard with all the tools you need.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced countdown and progress bar
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 3;
            const timerElement = document.getElementById('timer');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            // Initial state
            progressBar.style.width = '0%';
            progressText.textContent = '0%';

            // Update every second
            const interval = setInterval(function() {
                timeLeft--;
                timerElement.textContent = timeLeft;

                // Calculate progress
                const progress = (3 - timeLeft) * 33.33;
                progressBar.style.width = progress + '%';
                progressText.textContent = Math.round(progress) + '%';

                // Add some visual feedback
                if (timeLeft === 2) {
                    progressText.textContent = '33%';
                } else if (timeLeft === 1) {
                    progressText.textContent = '67%';
                } else if (timeLeft === 0) {
                    progressText.textContent = '100%';
                }

                if (timeLeft <= 0) {
                    clearInterval(interval);

                    // Add completion animation
                    progressBar.style.width = '100%';
                    progressText.textContent = '100%';

                    // Small delay before redirect for visual completion
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard.redirect') }}";
                    }, 500);
                }
            }, 1000);
        });
    </script>
</body>
</html>
