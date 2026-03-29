<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <a href="{{ route('admin.export.tickets') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Tickets (Excel/CSV)
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Total Clients</div>
                    <div class="text-3xl font-bold">{{ $totalUsers }}</div>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Total Organizers</div>
                    <div class="text-3xl font-bold">{{ $totalOrganizers }}</div>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 text-sm">Tickets Sold</div>
                    <div class="text-3xl font-bold">{{ $totalTicketsSold }}</div>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-green-200 bg-green-50">
                    <div class="text-green-600 font-bold text-sm">Total Revenue</div>
                    <div class="text-3xl font-bold text-green-700">${{ number_format($totalRevenue, 2) }}</div>
                    <div class="mt-3 text-sm font-semibold text-green-800 bg-green-200 inline-block px-2 py-1 rounded-md">
                        Platform Commission (2%): ${{ number_format($commission, 2) }}
                    </div>
                </div>
            </div>

            <!-- Top Organizer Stats -->
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Top Organizers</h3>
                    <a href="{{ route('admin.organizers.stats') }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-md text-sm font-bold hover:bg-indigo-600 hover:text-white transition">See all organisers</a>
                </div>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border-b">Name</th>
                            <th class="p-4 border-b">Artist/Band</th>
                            <th class="p-4 border-b">Tickets Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topOrganizers as $stat)
                            <tr>
                                <td class="p-4 border-b">{{ $stat['name'] }}</td>
                                <td class="p-4 border-b">{{ $stat['artist_name'] ?? 'N/A' }}</td>
                                <td class="p-4 border-b font-bold">{{ $stat['tickets_sold'] }}</td>
                            </tr>
                        @endforeach
                        @if($topOrganizers->isEmpty())
                            <tr><td colspan="3" class="p-4 text-center text-gray-500">No organizers found.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Manage Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center group">
                    <div>
                        <h4 class="font-bold">Manage Clients</h4>
                        <p class="text-sm text-gray-500">View or deactivate registered clients.</p>
                    </div>
                    <a href="{{ route('admin.users.index', ['role' => 'client']) }}" class="px-4 py-2 bg-blue-100 text-blue-700 rounded font-bold group-hover:bg-blue-600 group-hover:text-white transition">View Clients</a>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center group">
                    <div>
                        <h4 class="font-bold">Manage Organizers</h4>
                        <p class="text-sm text-gray-500">View or create event organizers.</p>
                    </div>
                    <a href="{{ route('admin.users.index', ['role' => 'organizer']) }}" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded font-bold group-hover:bg-emerald-600 group-hover:text-white transition">View Organizers</a>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center group">
                    <div>
                        <h4 class="font-bold">Manage Admins</h4>
                        <p class="text-sm text-gray-500">View or add administrative users.</p>
                    </div>
                    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-4 py-2 bg-red-100 text-red-700 rounded font-bold group-hover:bg-red-600 group-hover:text-white transition">View Admins</a>
                </div>

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center group border border-purple-200">
                    <div>
                        <h4 class="font-bold text-purple-900">Manage Events</h4>
                        <p class="text-sm text-purple-500">Modify, view or delete all platform events.</p>
                    </div>
                    <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded font-bold hover:bg-purple-700 shadow-md shadow-purple-600/30 transition">View Events</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
