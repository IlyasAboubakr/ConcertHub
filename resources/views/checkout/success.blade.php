<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-green-600 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ __('Order Successful!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
²
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold mb-2">Thank you, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600 text-lg">Your payment has been successfully processed.</p>
                    <p class="text-gray-800 text-lg font-semibold mt-2">The ticket has been sent to your email as a pdf file.</p>
                    <p class="text-gray-500 mt-2">Order Reference: <strong>#{{ $order->id }}</strong></p>
                </div>

                <div class="border-t border-gray-200 mt-8 pt-8">
                    <h4 class="font-bold text-xl mb-6">Your Tickets</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($order->orderItems as $item)
                            <div class="border border-indigo-200 rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                                <div class="bg-indigo-600 text-white p-4 text-center">
                                    <h5 class="font-bold text-lg leading-tight">{{ $item->ticketType->event->title }}</h5>
                                    <p class="text-indigo-200 text-sm mt-1">{{ $item->ticketType->event->event_date->format('M d, Y') }}</p>
                                </div>
                                <div class="p-6 bg-white text-center">
                                    <p class="font-bold text-gray-800 text-xl border-b pb-4 mb-4">{{ $item->ticketType->name }}</p>
                                    
                                    <div class="mb-6">
                                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Ticket Code</p>
                                        <p class="font-mono text-lg font-bold tracking-widest text-indigo-700">{{ $item->ticket_code }}</p>
                                    </div>
                                    
                                    <a href="{{ route('checkout.download', $item) }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-indigo-50 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-600 hover:text-white transition font-bold">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 text-center pt-8 border-t">
                    <a href="{{ route('client.dashboard') }}" class="text-indigo-600 font-semibold hover:text-indigo-800">
                        &larr; Return to My Dashboard
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
