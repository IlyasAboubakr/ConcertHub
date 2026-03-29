<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Event: ') . $event->title }}
            </h2>
            <div>
                <a href="{{ route('organizer.events.export', $event) }}" class="mr-2 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-green-700 shadow-md">Export Audit</a>
                <a href="{{ route('organizer.events.index') }}" class="px-4 py-2 bg-gray-600 text-white font-bold text-xs uppercase tracking-widest rounded hover:bg-gray-700 mr-2">Back</a>
                
                @can('update', $event)
                    <a href="{{ route('organizer.events.edit', $event) }}" class="px-4 py-2 bg-indigo-600 font-bold text-xs text-white uppercase tracking-widest rounded hover:bg-indigo-700">Edit</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Event Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Tickets Sold</div>
                    <div class="text-3xl font-extrabold text-indigo-700">{{ $totalSold }}</div>
                </div>
                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Net Revenue (after 2% commission)</div>
                    <div class="text-3xl font-extrabold text-green-700">${{ number_format($totalRevenue * 0.98, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Gross Revenue: ${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <!-- Event Details Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if($event->image)
                            <img src="{{ \Illuminate\Support\Str::startsWith($event->image, 'http') ? $event->image : Storage::url($event->image) }}" alt="Event Banner" class="w-full h-48 object-cover rounded mb-4">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-500">No Image Provided</div>
                        @endif

                        <h3 class="font-bold text-xl mb-2">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-600 mb-4 whitespace-nowrap overflow-hidden text-ellipsis">{{ $event->description }}</p>

                        <div class="text-sm space-y-2">
                            <p><strong>Date:</strong> {{ $event->event_date->format('M d, Y') }}</p>
                            <p><strong>Time:</strong> {{ date('H:i', strtotime($event->event_time)) }}</p>
                            <p><strong>Location:</strong> {{ $event->location }}</p>
                            <p><strong>Status:</strong> 
                                <span class="{{ $event->status === 'published' ? 'text-green-600' : 'text-gray-500' }} font-bold capitalize">{{ $event->status }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tickets Management Panel -->
                <div class="lg:col-span-2">
                    <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-lg">Ticket Types</h3>
                            
                            @can('update', $event)
                                <a href="{{ route('organizer.tickets.create', $event) }}" class="px-3 py-1 bg-green-600 text-white rounded flex items-center hover:bg-green-700 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Ticket Type
                                </a>
                            @else
                                <span class="text-xs text-gray-500 italic">Editing locked (Policy: 2 days)</span>
                            @endcan
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-4 border-b">Type Name</th>
                                        <th class="p-4 border-b">Price</th>
                                        <th class="p-4 border-b">Available Limit</th>
                                        <th class="p-4 border-b">Sold</th>
                                        <th class="p-4 border-b text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->ticketTypes as $ticket)
                                        @php
                                            $sold = $ticket->orderItems()->whereHas('order', function($q) {
                                                $q->where('status', 'completed');
                                            })->sum('quantity');
                                        @endphp
                                        <tr>
                                            <td class="p-4 border-b font-medium">{{ $ticket->name }}</td>
                                            <td class="p-4 border-b">${{ number_format($ticket->price, 2) }}</td>
                                            <td class="p-4 border-b">{{ $ticket->quantity_available }}</td>
                                            <td class="p-4 border-b font-bold text-indigo-600">{{ $sold }}</td>
                                            <td class="p-4 border-b text-right">
                                                @can('update', $event)
                                                    <a href="{{ route('organizer.tickets.edit', [$event, $ticket]) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                                    
                                                    @if($sold == 0)
                                                        <form action="{{ route('organizer.tickets.destroy', [$event, $ticket]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this ticket type?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400 text-xs italic" title="Has sales">Delete locked</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 italic text-xs">Locked</span>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @if($event->ticketTypes->isEmpty())
                                        <tr>
                                            <td colspan="5" class="p-4 text-center text-gray-500 py-8">
                                                <p>No ticket types have been created yet.</p>
                                                @if($event->status === 'published')
                                                    <p class="text-red-500 mt-2 text-sm">Warning: Users cannot purchase tickets until a ticket type is created!</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
