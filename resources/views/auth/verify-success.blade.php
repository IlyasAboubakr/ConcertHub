<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="flex justify-center mb-6 text-green-500">
            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900">Email Verified!</h2>
        <p class="text-gray-500 mt-4 font-medium text-lg">Your account is successfully verified, you can browse the events now.</p>
    </div>

    <div class="mt-8">
        <a href="{{ route('events.index') }}" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-lg shadow-md text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
            Browse Events
        </a>
    </div>
</x-guest-layout>
