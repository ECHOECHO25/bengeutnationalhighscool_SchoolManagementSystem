

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

     <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased bg-main">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <header class="w-full lg:max-w-4xl max-w-[335px] mx-auto text-sm p-6 lg:p-8">

            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="inline-block px-5 py-1.5 border border-[#19140035] hover:border-[#1915014a] text-white rounded-sm text-sm leading-normal transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-block px-5 py-1.5  text-white hover:border rounded-sm text-sm leading-normal transition-colors">
                            Log in
                        </a>

                        {{-- @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-block px-5 py-1.5 border hover:bg-white hover:text-gray-900  text-white rounded-sm text-sm leading-normal transition-colors">
                                Register
                            </a>
                        @endif --}}
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        <main class="w-full lg:max-w-4xl max-w-[335px] mx-auto p-6 lg:p-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 rounded-xl shadow-md overflow-hidden mb-10">
                <div class="p-8 text-white text-center">
                    <h1 class="text-3xl font-bold mb-3">Welcome to {{ config('app.name') }}</h1>
                    <p class="text-blue-100">School Management System</p>
                </div>
            </div>

            <!-- School Image -->
            <div class="relative h-64 w-full rounded-lg shadow-md overflow-hidden mb-10">
                <img src="{{ asset('images/BeNHS.jpg') }}"
                     alt="Benguet National High School Campus"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                    <div class="text-center px-4">
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Benguet National High School</h2>
                        <p class="text-lg text-blue-100">Excellence in Education </p>
                    </div>
                </div>
            </div>

            <!-- School Description -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-10">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">About Our School</h2>
                <div class="space-y-4 text-gray-700 dark:text-gray-300">
                    <p>
                        Benguet National High School (BeNHS) is the premier secondary educational institution in La Trinidad, Benguet. Our school has been at the forefront of providing quality education to the youth of Benguet Province for over seven decades.
                    </p>
                    <p>
                        We take pride in our rich history and tradition of academic excellence, consistently producing graduates who excel in various fields both locally and internationally. Our comprehensive curriculum combines the Department of Education's K-12 program with specialized tracks to cater to diverse student needs and aspirations.
                    </p>
                    <p>
                        Our campus features modern facilities including science laboratories, computer rooms, a library, and sports facilities to support holistic student development. We believe in molding well-rounded individuals who are not only academically competent but also socially aware and morally upright.
                    </p>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <!-- Feature 1 -->
                <div class="bg-white text-main rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-start">
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Student Management</h3>
                            <p class="text-gray-600 dark:text-gray-400">Efficiently manage student records, attendance, and academic progress.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white text-main rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-start">
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Faculty Tools</h3>
                            <p class="text-gray-600 dark:text-gray-400">Comprehensive tools for teachers to manage classes and grades.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white text-main rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-start">
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-purple-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Reporting</h3>
                            <p class="text-gray-600 dark:text-gray-400">Generate detailed reports for academic performance and administration.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white text-main rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-start">
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-yellow-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Scheduling</h3>
                            <p class="text-gray-600 dark:text-gray-400">Easy management of class schedules and school events calendar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center text-white">
                <h3 class="text-xl font-semibold mb-4">Ready to get started?</h3>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-sm transition-colors">
                        Login to Your Account
                    </a>
                </div>
            </div>
        </main>
    </div>
      @filamentScripts
        @vite('resources/js/app.js')
</body>

</html>
