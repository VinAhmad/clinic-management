<nav x-data="{ open: false }" class="bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 shadow-lg border-b-0">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-white hover:text-gray-200 transition duration-200" />
                    </a>
                    <span class="ml-3 text-white font-bold text-lg hidden sm:block">Clinic Management</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'doctor')
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('Appointments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('medical-records.index')" :active="request()->routeIs('medical-records.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('Medical Records') }}
                        </x-nav-link>
                        <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('My Schedule') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'patient')
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('My Appointments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('medical-records.index')" :active="request()->routeIs('medical-records.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('My Records') }}
                        </x-nav-link>
                        <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('Payments') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('Appointments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('Doctors') }}
                        </x-nav-link>
                        <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('Payments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('payments.reports')" :active="request()->routeIs('payments.reports')" class="text-white/80 hover:text-white border-white/30 hover:border-white">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-white/30 text-sm leading-4 font-medium rounded-lg text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div class="text-left">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-white/70">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white/80 hover:text-white hover:bg-white/20 focus:outline-none focus:bg-white/20 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-indigo-800/50 backdrop-blur-lg">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/80 hover:text-white hover:bg-white/20">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'doctor')
                <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Appointments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('medical-records.index')" :active="request()->routeIs('medical-records.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Medical Records') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('My Schedule') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'patient')
                <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('My Appointments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('medical-records.index')" :active="request()->routeIs('medical-records.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('My Records') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Payments') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Appointments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Doctors') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Payments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payments.reports')" :active="request()->routeIs('payments.reports')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-white/70">{{ Auth::user()->email }}</div>
                <div class="text-xs text-white/60 mt-1">{{ ucfirst(Auth::user()->role) }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white/80 hover:text-white hover:bg-white/20">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-white/80 hover:text-white hover:bg-white/20">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
