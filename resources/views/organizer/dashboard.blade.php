<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organizer Dashboard: ') . $organizer->artist_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Total Events</div>
                    <div class="text-3xl font-bold">{{ $totalEvents }}</div>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Published Events</div>
                    <div class="text-3xl font-bold">{{ $publishedEvents }}</div>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Tickets Sold</div>
                    <div class="text-3xl font-bold">{{ $totalTicketsSold }}</div>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Total Revenue</div>
                    <div class="text-3xl font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>

            <!-- Manage Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center">
                    <div>
                        <h4 class="font-bold">Manage Events</h4>
                        <p class="text-sm text-gray-500">Create, edit, and publish your events.</p>
                    </div>
                    <a href="{{ route('organizer.events.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Go to Events</a>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center border-l-4 border-indigo-500">
                    <div>
                        <h4 class="font-bold">Create New Event</h4>
                        <p class="text-sm text-gray-500">Start planning your next concert quickly.</p>
                    </div>
                    <a href="{{ route('organizer.events.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Create Event</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
