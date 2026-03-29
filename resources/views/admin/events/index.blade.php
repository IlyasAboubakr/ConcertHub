<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Events') }}
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
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border-b">ID</th>
                            <th class="p-4 border-b">Title</th>
                            <th class="p-4 border-b">Organizer</th>
                            <th class="p-4 border-b">Date</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td class="p-4 border-b">{{ $event->id }}</td>
                                <td class="p-4 border-b">{{ $event->title }}</td>
                                <td class="p-4 border-b">{{ $event->organizer->name ?? 'Unknown' }}</td>
                                <td class="p-4 border-b">{{ $event->event_date->format('M d, Y') }} at {{ date('H:i', strtotime($event->event_time)) }}</td>
                                <td class="p-4 border-b capitalize">
                                    <span class="{{ $event->status === 'published' ? 'text-green-600' : 'text-gray-500' }} font-bold">
                                        {{ $event->status }}
                                    </span>
                                </td>
                                <td class="p-4 border-b text-right">
                                    <a href="{{ route('admin.events.show', $event) }}" class="text-blue-600 hover:text-blue-900 mr-3">Stats</a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="text-emerald-600 hover:text-emerald-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to completely delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if($events->isEmpty())
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">No events found.</td>
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
