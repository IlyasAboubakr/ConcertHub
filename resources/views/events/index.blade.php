<x-public-layout>
    <div class="bg-gray-900 pb-24 pt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-white mb-4">Discover Events</h1>
            <p class="text-xl text-gray-300">Find the best live music happening near you or around the world.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 mb-16 relative z-10">
        <form action="{{ route('events.index') }}" method="GET" class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 flex flex-col md:flex-row gap-6 items-end">
            <div class="w-full md:w-1/3">
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Event Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="title" id="title" value="{{ request('title') }}" class="pl-10 block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-medium py-3 shadow-sm" placeholder="e.g. Summer Festival...">
                </div>
            </div>
            
            <div class="w-full md:w-1/3">
                <label for="date" class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="pl-10 block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-medium py-3 shadow-sm">
                </div>
            </div>
            
            <div class="w-full md:w-1/3 flex gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg shadow-indigo-600/30 flex justify-center items-center">
                    Search Events
                </button>
                @if(request()->hasAny(['title', 'date']))
                    <a href="{{ route('events.index') }}" class="bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-200 transition duration-300 flex justify-center items-center border border-gray-200">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="mt-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($events as $event)
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="relative overflow-hidden h-60">
                            @if($event->image)
                                <img src="{{ \Illuminate\Support\Str::startsWith($event->image, 'http') ? $event->image : Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full bg-indigo-100 flex items-center justify-center text-indigo-400">
                                    <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm12 0c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-sm">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider text-center">{{ $event->event_date->format('M') }}</p>
                                <p class="text-xl font-extrabold text-indigo-600 text-center leading-none">{{ $event->event_date->format('d') }}</p>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                            <p class="text-gray-500 text-sm font-medium mb-4">By {{ $event->organizer?->artist_name ?? $event->organizer?->name ?? 'Unknown Organizer' }}</p>
                            
                            <div class="flex items-start text-gray-600 text-sm mb-4">
                                <svg class="w-5 h-5 mr-2 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="line-clamp-2 leading-relaxed">{{ $event->location }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600 text-sm mb-6">
                                <svg class="w-5 h-5 mr-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ date('g:i A', strtotime($event->event_time)) }}</span>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="text-sm font-bold text-gray-900">
                                    @if($event->ticketTypes->min('price'))
                                        From ${{ number_format($event->ticketTypes->min('price'), 2) }}
                                    @else
                                        TBA
                                    @endif
                                </div>
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-50 text-purple-700 font-bold rounded-lg hover:bg-purple-600 hover:text-white transition duration-200">
                                        Manage Event
                                    </a>
                                @elseif(!auth()->check() || auth()->id() !== $event->organizer_id)
                                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-50 text-indigo-700 font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition duration-200">
                                        Get Tickets
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($events->isEmpty())
                <div class="text-center bg-white rounded-2xl p-12 border border-dashed border-gray-300 mt-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-900">No events found matching your search.</h3>
                    <p class="mt-2 text-gray-500">Try adjusting your filters or <a href="{{ route('events.index') }}" class="text-indigo-600 underline">view all events</a>.</p>
                </div>
            @endif

            <div class="mt-16">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-public-layout>
