<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'ConcertHub') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900 selection:bg-indigo-500 selection:text-white flex flex-col min-h-screen">
        
        <!-- Navigation -->
        <nav class="{{ isset($transparentNav) && $transparentNav ? 'absolute top-0 w-full z-50 bg-transparent border-b border-white/10' : 'relative bg-gray-900 w-full z-50 border-b border-gray-800 shadow-md' }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2 drop-shadow-md">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm12 0c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"></path></svg>
                            ConcertHub
                        </a>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('events.index') }}" class="text-white/90 hover:text-white font-semibold transition">Events</a>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-white/90 hover:text-white font-semibold transition">Dashboard</a>
                            @elseif(auth()->user()->role === 'organizer')
                                <a href="{{ route('organizer.dashboard') }}" class="text-white/90 hover:text-white font-semibold transition">Dashboard</a>
                            @else
                                <a href="{{ route('client.dashboard') }}" class="text-white/90 hover:text-white font-semibold transition">My Tickets</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="text-white/90 hover:text-white font-semibold transition ml-4">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white/90 hover:text-white font-semibold transition ml-4">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-white/90 hover:text-white font-semibold transition">Sign In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full font-bold hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/30">Join Free</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            @if (isset($header))
                <header class="bg-white shadow border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 border-t border-gray-800 text-gray-400 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
                <div class="flex items-center justify-center space-x-2 mb-2">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm12 0c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"></path></svg>
                    <span class="text-2xl font-extrabold tracking-tight text-white">ConcertHub</span>
                </div>
                <p class="mb-4">&copy; {{ date('Y') }} ConcertHub Inc. All rights reserved.</p>
                <div class="flex justify-center space-x-6 text-sm">
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                    <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy Policy</a>
                </div>
            </div>
        </footer>
    </body>
</html>
