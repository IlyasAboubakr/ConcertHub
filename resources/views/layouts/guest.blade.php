<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ConcertHub') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex">
            <!-- Left Side: Image/Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-indigo-900 text-white relative flex-col justify-center items-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-800 to-purple-900 opacity-90"></div>
                <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center"></div>
                <div class="relative z-10 p-12 text-center max-w-lg">
                    <a href="{{ url('/') }}" class="inline-block mb-8">
                        <div class="flex items-center justify-center space-x-3">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm12 0c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"></path></svg>
                            <h1 class="text-5xl font-extrabold tracking-tight">ConcertHub</h1>
                        </div>
                    </a>
                    <p class="text-xl text-indigo-100 font-medium leading-relaxed">Your ultimate platform for discovering and managing live music experiences.</p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 relative">
                <!-- Mobile only background -->
                <div class="lg:hidden absolute inset-0 bg-indigo-900 overflow-hidden">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')] bg-cover bg-center"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-indigo-900 to-transparent"></div>
                </div>

                <div class="w-full max-w-md relative z-10">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-10">
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center space-x-2 text-white">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm12 0c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"></path></svg>
                            <h1 class="text-4xl font-extrabold tracking-tight">ConcertHub</h1>
                        </a>
                    </div>

                    <div class="bg-white p-10 rounded-3xl shadow-2xl border border-gray-100/50 backdrop-blur-sm">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
