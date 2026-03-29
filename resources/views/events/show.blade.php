<x-public-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Event Header Image -->
                @if($event->image)
                    <div class="w-full h-64 md:h-96 relative">
                        <img src="{{ \Illuminate\Support\Str::startsWith($event->image, 'http') ? $event->image : Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                            <div class="p-8 text-white w-full">
                                <span class="bg-indigo-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-2 inline-block">Live Music</span>
                                <h1 class="text-4xl md:text-5xl font-extrabold mb-2">{{ $event->title }}</h1>
                                <p class="text-lg opacity-90">By {{ $event->organizer?->artist_name ?? $event->organizer?->name ?? 'Unknown Organizer' }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-8 bg-indigo-700 text-white">
                        <span class="bg-white text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-2 inline-block">Live Music</span>
                        <h1 class="text-4xl md:text-5xl font-extrabold mb-2">{{ $event->title }}</h1>
                        <p class="text-lg opacity-90">By {{ $event->organizer?->artist_name ?? $event->organizer?->name ?? 'Unknown Organizer' }}</p>
                    </div>
                @endif

                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Left Column: Details -->
                    <div class="md:col-span-2 space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold mb-4">About This Event</h3>
                            <div class="prose max-w-none text-gray-600 leading-relaxed whitespace-pre-line">
                                {{ $event->description }}
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6 flex flex-col sm:flex-row gap-6">
                            <div class="flex-1">
                                <div class="flex items-center text-indigo-600 mb-2">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="font-bold">Date and Time</span>
                                </div>
                                <p class="text-gray-700">{{ $event->event_date->format('l, F j, Y') }}</p>
                                <p class="text-gray-700">{{ date('g:i A', strtotime($event->event_time)) }}</p>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center text-indigo-600 mb-2">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="font-bold">Location</span>
                                </div>
                                <p class="text-gray-700">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Tickets -->
                    <div class="md:col-span-1 border-t md:border-t-0 md:border-l border-gray-100 pt-8 md:pt-0 md:pl-8">
                        <h3 class="text-2xl font-bold mb-6">Select Tickets</h3>
                        
                        @if($event->ticketTypes->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-lg">
                                Tickets are not yet available for this event. Please check back later.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($event->ticketTypes as $ticket)
                                    <div class="border rounded-lg p-4 {{ $ticket->quantity_available > 0 ? 'bg-white' : 'bg-gray-50 opacity-60' }}">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-bold text-lg">{{ $ticket->name }}</h4>
                                                <p class="text-indigo-600 font-bold">${{ number_format($ticket->price, 2) }}</p>
                                            </div>
                                            @if($ticket->quantity_available <= 0)
                                                <span class="text-red-500 font-bold uppercase text-sm px-2 py-1 bg-red-50 rounded">Sold Out</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="pt-4">
                                    @if(auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.events.edit', $event) }}" class="block text-center w-full bg-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-purple-700 transition duration-300 shadow-md">
                                            Manage Event
                                        </a>
                                    @elseif(!auth()->check() || auth()->user()->role === 'client')
                                        <a href="{{ route('tickets.show', $event->id) }}" class="block text-center w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md">
                                            Get Tickets
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
