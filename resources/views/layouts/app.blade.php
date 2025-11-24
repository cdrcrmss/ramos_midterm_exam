<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TwitterClone')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:bg-gray-50 rounded-full px-3 py-2 transition-colors duration-200">
                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    <span class="font-bold text-xl text-gray-900">TwitterClone</span>
                </a>

                @auth
                    <!-- Search Bar - Center -->
                    <div class="flex-1 max-w-xl mx-8">
                        <form action="{{ route('search.index') }}" method="GET">
                            <input type="text" 
                                   name="q" 
                                   value="{{ request('q') }}"
                                   placeholder="Search for people and tweets..." 
                                   class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all duration-200 text-sm">
                        </form>
                    </div>

                    <!-- Right Side Icons -->
                    <div class="flex items-center space-x-6">
                        <!-- Search -->
                        <a href="{{ route('search.index') }}" 
                           class="relative p-3 text-gray-700 hover:text-blue-500 hover:bg-blue-50 rounded-full transition-all duration-200 group">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                                Search
                            </div>
                        </a>

                        <!-- Profile -->
                        <a href="{{ route('users.profile', auth()->user()) }}" 
                           class="flex items-center space-x-3 text-gray-700 hover:text-blue-500 font-medium hover:bg-gray-50 rounded-full px-4 py-2 transition-all duration-200">
                            @if(auth()->user()->profile_picture)
                                <img class="w-8 h-8 rounded-full object-cover border-2 border-gray-200" 
                                     src="{{ auth()->user()->profile_picture_url }}" 
                                     alt="{{ auth()->user()->display_name }}">
                            @else
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ auth()->user()->initials }}
                                </div>
                            @endif
                            <span class="hidden lg:inline">{{ auth()->user()->display_name }}</span>
                        </a>

                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-full font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-blue-500 font-medium hover:bg-gray-50 rounded-full px-4 py-2 transition-all duration-200">Login</a>
                        <a href="{{ route('register') }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-full font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            Sign Up
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
