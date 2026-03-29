<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tickets Sold per Organizer') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border-b">Name</th>
                            <th class="p-4 border-b">Artist/Band</th>
                            <th class="p-4 border-b text-right">Tickets Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organizerStats as $stat)
                            <tr>
                                <td class="p-4 border-b">{{ $stat['name'] }}</td>
                                <td class="p-4 border-b">{{ $stat['artist_name'] ?? 'N/A' }}</td>
                                <td class="p-4 border-b text-right font-bold text-lg text-indigo-700">{{ $stat['tickets_sold'] }}</td>
                            </tr>
                        @endforeach
                        @if($organizerStats->isEmpty())
                            <tr><td colspan="3" class="p-4 text-center text-gray-500">No organizers found.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
