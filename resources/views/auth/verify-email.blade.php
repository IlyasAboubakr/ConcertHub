<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Verify Your Email</h2>
        <p class="text-gray-500 mt-2 font-medium">Almost there! We've sent an email to <strong>{{ Auth::user()->email }}</strong>.</p>
    </div>

    <div class="mb-6 text-sm text-gray-600 bg-gray-50 p-4 rounded-lg border border-gray-200">
        {{ __('Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-medium text-sm text-green-700 bg-green-50 p-4 rounded-lg border border-green-200">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-bold text-gray-600 hover:text-indigo-600 transition-colors underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>

    <!-- Polling for auto-redirect -->
    <script>
        setInterval(function() {
            fetch('{{ route('dashboard') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if(response.status === 200 || response.ok) {
                    window.location.href = '{{ route('dashboard') }}';
                }
            })
            .catch(error => console.error(error));
        }, 3000); 
    </script>
</x-guest-layout>
