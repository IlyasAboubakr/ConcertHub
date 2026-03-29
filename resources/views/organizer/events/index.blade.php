<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Events') }}
            </h2>
            <a href="{{ route('organizer.events.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Create New Event</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border-b">ID</th>
                            <th class="p-4 border-b">Event Image</th>
                            <th class="p-4 border-b">Title</th>
                            <th class="p-4 border-b">Date & Time</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td class="p-4 border-b">{{ $event->id }}</td>
                                <td class="p-4 border-b">
                                    @if($event->image)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($event->image, 'http') ? $event->image : Storage::url($event->image) }}" alt="Event image" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded text-xs text-gray-500">No Image</div>
                                    @endif
                                </td>
                                <td class="p-4 border-b font-bold">{{ $event->title }}</td>
                                <td class="p-4 border-b">{{ $event->event_date->format('M d, Y') }} at {{ date('H:i', strtotime($event->event_time)) }}</td>
                                <td class="p-4 border-b capitalize">
                                    <span class="{{ $event->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded text-sm font-semibold">
                                        {{ $event->status }}
                                    </span>
                                </td>
                                <td class="p-4 border-b text-right">
                                    <a href="{{ route('organizer.events.show', $event) }}" class="text-green-600 font-bold hover:text-green-900 mr-3">Manage & Stats</a>
                                    {{-- Use Policy to check if can edit (2-day rule) --}}
                                    @can('update', $event)
                                        <a href="{{ route('organizer.events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed mr-2" title="Cannot edit within 2 days of event">Edit</span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        
                        @if($events->isEmpty())
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">You haven't created any events yet!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
