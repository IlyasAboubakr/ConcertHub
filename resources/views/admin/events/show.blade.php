<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event Stats: ') . $event->title }}
            </h2>
            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to Events</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Total Tickets Sold for this Event</div>
                    <div class="text-3xl font-bold">{{ $totalTicketsSold }}</div>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Total Revenue for this Event</div>
                    <div class="text-3xl font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="font-bold text-lg mb-4">Event Details</h3>
                <p><strong>Organizer:</strong> {{ $event->organizer->name ?? 'N/A' }} ({{ $event->organizer->artist_name ?? 'N/A' }})</p>
                <p><strong>Date & Time:</strong> {{ $event->event_date->format('M d, Y') }} at {{ date('H:i', strtotime($event->event_time)) }}</p>
                <p><strong>Location:</strong> {{ $event->location }}</p>
                <p><strong>Status:</strong> <span class="capitalize {{ $event->status === 'published' ? 'text-green-600' : 'text-gray-500' }}">{{ $event->status }}</span></p>
            </div>

            <!-- Ticket Breakdown -->
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="font-bold text-lg mb-4">Ticket Sales Breakdown</h3>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border-b">Ticket Type</th>
                            <th class="p-4 border-b">Price</th>
                            <th class="p-4 border-b">Available Limit</th>
                            <th class="p-4 border-b">Sold</th>
                            <th class="p-4 border-b text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->ticketTypes as $ticketType)
                            <tr>
                                <td class="p-4 border-b">{{ $ticketType->name }}</td>
                                <td class="p-4 border-b">${{ number_format($ticketType->price, 2) }}</td>
                                <td class="p-4 border-b">{{ $ticketType->quantity_available }}</td>
                                <td class="p-4 border-b font-bold">{{ $ticketType->sold_count }}</td>
                                <td class="p-4 border-b text-right text-green-600 font-bold">${{ number_format($ticketType->revenue_generated, 2) }}</td>
                            </tr>
                        @endforeach
                        
                        @if($event->ticketTypes->isEmpty())
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">No ticket types defined for this event yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
