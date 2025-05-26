<x-guest-layout>
    <div class="flex w-full min-h-screen">
        <!-- Left Panel - Welcome Section -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
                <div class="mb-8">
                    <x-application-logo class="w-24 h-24 fill-current text-white" />
                </div>
                <h1 class="text-4xl font-bold mb-6 text-center">Welcome Back</h1>
                <p class="text-xl text-center leading-relaxed opacity-90 max-w-md">
                    Sign in to access your clinic management dashboard and continue providing excellent healthcare services.
                </p>
                <div class="mt-12 grid grid-cols-3 gap-8 opacity-20">
                    <div class="w-16 h-16 bg-white/20 rounded-full"></div>
                    <div class="w-16 h-16 bg-white/20 rounded-full"></div>
                    <div class="w-16 h-16 bg-white/20 rounded-full"></div>
                </div>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full"></div>
            <div class="absolute bottom-10 left-10 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <x-application-logo class="w-16 h-16 fill-current text-indigo-600" />
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">Sign In</h2>
                        <p class="text-gray-600 mt-2">Enter your credentials to access your account</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
                            <x-text-input id="email"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Enter your email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                            <x-text-input id="password"
                                class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-500 font-medium" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>

                        <x-primary-button class="w-full justify-center py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg transform transition duration-200 hover:scale-[1.02]">
                            {{ __('Sign In') }}
                        </x-primary-button>

                        @if (Route::has('register'))
                            <div class="text-center">
                                <span class="text-gray-600">Don't have an account?</span>
                                <a class="text-indigo-600 hover:text-indigo-500 font-medium ml-1" href="{{ route('register') }}">
                                    {{ __('Create one here') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
