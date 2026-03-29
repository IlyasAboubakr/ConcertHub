<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tickets & Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="font-bold text-lg mb-4">Order History</h3>
                
                @if($orders->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-lg">You haven't bought any tickets yet.</p>
                        <a href="{{ url('/') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Browse Events</a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($orders as $order)
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center flex-wrap gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Order Placed</p>
                                        <p class="font-bold">{{ $order->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Total Amount</p>
                                        <p class="font-bold">${{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Order #</p>
                                        <p class="font-bold">{{ $order->id }}</p>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 bg-{{ $order->status === 'completed' ? 'green' : 'yellow' }}-100 text-{{ $order->status === 'completed' ? 'green' : 'yellow' }}-800 rounded-full text-xs font-semibold capitalize">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="text-sm text-gray-500 border-b">
                                                <th class="pb-2">Event</th>
                                                <th class="pb-2">Ticket Type</th>
                                                <th class="pb-2">Qty</th>
                                                <th class="pb-2">Price</th>
                                                <th class="pb-2 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($order->orderItems as $item)
                                                <tr>
                                                    <td class="py-3">
                                                        <a href="{{ route('events.show', $item->ticketType->event) }}" class="font-bold text-indigo-600 hover:underline">
                                                            {{ $item->ticketType->event->title }}
                                                        </a>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $item->ticketType->event->event_date->format('M d, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="py-3">{{ $item->ticketType->name }}</td>
                                                    <td class="py-3">{{ $item->quantity }}</td>
                                                    <td class="py-3">${{ number_format($item->price, 2) }}</td>
                                                    <td class="py-3 text-right">
                                                        @if($order->status === 'completed')
                                                            <a href="{{ route('checkout.download', $item) }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                Download Ticket
                                                            </a>
                                                        @else
                                                            <span class="text-gray-400 text-sm italic">Pending Payment</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
