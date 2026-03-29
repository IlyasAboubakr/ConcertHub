<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Forgot Password</h2>
        <p class="text-gray-500 mt-2 font-medium">No problem. Just let us know your email address and we will email you a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="font-semibold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition px-4 py-3 bg-gray-50" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Remember your password? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition">Sign in here</a>
            </p>
        </div>
    </form>
</x-guest-layout>
