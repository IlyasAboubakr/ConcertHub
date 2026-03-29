<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Welcome Back</h2>
        <p class="text-gray-500 mt-2 font-medium">Please enter your details to sign in.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition px-4 py-3 bg-gray-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-700" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition px-4 py-3 bg-gray-50"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition cursor-pointer" name="remember">
                <span class="ms-3 text-sm font-medium text-gray-600">{{ __('Remember me for 30 days') }}</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                {{ __('Sign In') }}
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition">Sign up for free</a>
            </p>
        </div>
    </form>
</x-guest-layout>
