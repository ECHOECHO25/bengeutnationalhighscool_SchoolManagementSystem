<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false, sidebarOpen: false }" class="bg-main1 border-b border-gray-500 top-0 sticky">
    @php
        $currentSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
    @endphp
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">


                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- hamburger menu -->
                <aside class="grid place-content-center ms-8">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                            <path d="M4 5h16" />
                            <path d="M4 12h16" />
                            <path d="M4 19h16" />
                        </svg>
                    </button>
                </aside>



                <!-- Navigation Links -->
                <div class="hidden space-x-5 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Home') }}
                    </x-nav-link>
                    @if (auth()->user()->role == 'admin')
                        <x-nav-link :href="route('admin.school-year')" :active="request()->routeIs('admin.school-year')" wire:navigate>
                            {{ __('School Years') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.audit')" :active="request()->routeIs('admin.audit')" wire:navigate>
                            {{ __('Audit Logs') }}
                        </x-nav-link>
                         <x-nav-link :href="route('admin.backup-restore')" :active="request()->routeIs('admin.backup-restore')" wire:navigate>
                            {{ __('Backup & Restore') }}
                        </x-nav-link>
                         <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" wire:navigate>
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endif


                </div>


            </div>

            <div class="hidden space-x-5 sm:-my-px sm:ms-10 sm:flex">
                <div class="flex items-center">

                    @if ($currentSchoolYear)
                        <x-filament::badge icon="heroicon-s-calendar-date-range">
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($currentSchoolYear->start_date)->format('Y') }} -
                                {{ \Carbon\Carbon::parse($currentSchoolYear->end_date)->format('Y') }}</span>
                        </x-filament::badge>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white  hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="sidebarOpen" @click.away="sidebarOpen = false"
        class="fixed inset-y-0 left-0 w-64 bg-main text-white p-4 z-50 transform transition-transform duration-300 ease-in-out shadow-xl"
        :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Navigation</h3>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Current School Year Display (Sidebar) -->
        @if ($currentSchoolYear)
            <div class="mb-4 p-3 bg-gray-700 rounded-lg">
                <div class="font-medium text-sm">Current School Year:</div>
                <div class="font-bold"></div>
                <div class="text-xs text-gray-300 mt-1">
                    <span class="font-semibold">
                        {{ \Carbon\Carbon::parse($currentSchoolYear->start_date)->format('Y') }} -
                        {{ \Carbon\Carbon::parse($currentSchoolYear->end_date)->format('Y') }}</span>
                </div>
            </div>
        @endif
        @switch(auth()->user()->role)
            @case('admin')
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                                <rect width="7" height="9" x="3" y="3" rx="1" />
                                <rect width="7" height="5" x="14" y="3" rx="1" />
                                <rect width="7" height="9" x="14" y="12" rx="1" />
                                <rect width="7" height="5" x="3" y="16" rx="1" />
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.teacher') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.teacher') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <circle cx="9" cy="7" r="4" />
                            </svg>
                            <span class="ml-3">Teachers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.classroom') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.classroom') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-warehouse-icon lucide-warehouse">
                                <path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11" />
                                <path
                                    d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z" />
                                <path d="M6 13h12" />
                                <path d="M6 17h12" />
                            </svg>
                            <span class="ml-3">Classrooms</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.enrollment') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.enrollment') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-grid2x2-check-icon lucide-grid-2x2-check">
                                <path
                                    d="M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3" />
                                <path d="m16 19 2 2 4-4" />
                            </svg>
                            <span class="ml-3">Enlist</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.exam') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.exam') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-file-badge-icon lucide-file-badge">
                                <path d="M12 22h6a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3.072" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path
                                    d="m6.69 16.479 1.29 4.88a.5.5 0 0 1-.698.591l-1.843-.849a1 1 0 0 0-.88.001l-1.846.85a.5.5 0 0 1-.693-.593l1.29-4.88" />
                                <circle cx="5" cy="14" r="3" />
                            </svg>
                            <span class="ml-3">Grading</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-notebook-text-icon lucide-notebook-text">
                                <path d="M2 6h4" />
                                <path d="M2 10h4" />
                                <path d="M2 14h4" />
                                <path d="M2 18h4" />
                                <rect width="16" height="20" x="4" y="2" rx="2" />
                                <path d="M9.5 8h5" />
                                <path d="M9.5 12H16" />
                                <path d="M9.5 16H14" />
                            </svg>
                            <span class="ml-3">Subjects</span>
                        </a>
                    </li> --}}
                    {{-- <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-notepad-text-icon lucide-notepad-text">
                                <path d="M8 2v4" />
                                <path d="M12 2v4" />
                                <path d="M16 2v4" />
                                <rect width="16" height="18" x="4" y="4" rx="2" />
                                <path d="M8 10h6" />
                                <path d="M8 14h8" />
                                <path d="M8 18h5" />
                            </svg>
                            <span class="ml-3">Grades</span>
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ route('admin.student') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admin.student') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                <path d="M18 21a8 8 0 0 0-16 0" />
                                <circle cx="10" cy="8" r="5" />
                                <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                            </svg>
                            <span class="ml-3">Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reset-passwords') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-calendar-range-icon lucide-calendar-range">
                                <rect width="18" height="18" x="3" y="4" rx="2" />
                                <path d="M16 2v4" />
                                <path d="M3 10h18" />
                                <path d="M8 2v4" />
                                <path d="M17 14h-6" />
                                <path d="M13 18H7" />
                                <path d="M7 14h.01" />
                                <path d="M17 18h.01" />
                            </svg>
                            <span class="ml-3">Reset Passwords</span>
                        </a>
                    </li>
                </ul>
            @break

            @case('teacher')
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('teacher.dashboard') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                                <rect width="7" height="9" x="3" y="3" rx="1" />
                                <rect width="7" height="5" x="14" y="3" rx="1" />
                                <rect width="7" height="9" x="14" y="12" rx="1" />
                                <rect width="7" height="5" x="3" y="16" rx="1" />
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    @if (\App\Models\Classroom::where('teacher_id', auth()->user()->teacher->id)->get()->count() > 0)
                        <li>
                            <a href="{{ route('teacher.student') }}"
                                class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('teacher.student') ? 'bg-gray-700' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                    <path d="M18 21a8 8 0 0 0-16 0" />
                                    <circle cx="10" cy="8" r="5" />
                                    <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                                </svg>
                                <span class="ml-3">Advisory Class</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('teacher.subject') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('teacher.subject') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-notebook-text-icon lucide-notebook-text">
                                <path d="M2 6h4" />
                                <path d="M2 10h4" />
                                <path d="M2 14h4" />
                                <path d="M2 18h4" />
                                <rect width="16" height="20" x="4" y="2" rx="2" />
                                <path d="M9.5 8h5" />
                                <path d="M9.5 12H16" />
                                <path d="M9.5 16H14" />
                            </svg>
                            <span class="ml-3">Subjects</span>
                        </a>
                    </li>
                </ul>
            @break

            @case('student')
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('student.dashboard') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                                <rect width="7" height="9" x="3" y="3" rx="1" />
                                <rect width="7" height="5" x="14" y="3" rx="1" />
                                <rect width="7" height="9" x="14" y="12" rx="1" />
                                <rect width="7" height="5" x="3" y="16" rx="1" />
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.grade') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-notepad-text-icon lucide-notepad-text">
                                <path d="M8 2v4" />
                                <path d="M12 2v4" />
                                <path d="M16 2v4" />
                                <rect width="16" height="18" x="4" y="4" rx="2" />
                                <path d="M8 10h6" />
                                <path d="M8 14h8" />
                                <path d="M8 18h5" />
                            </svg>
                            <span class="ml-3">My Grade</span>
                        </a>
                    </li>

                </ul>
            @break

            @default
        @endswitch
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
