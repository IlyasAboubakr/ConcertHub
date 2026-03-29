<x-public-layout transparent-nav="true">
        <!-- Hero Section -->
        <div class="relative bg-gray-900 text-white min-h-[85vh] flex items-center justify-center pt-20">
            <div class="absolute inset-0 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1470229722913-7c092bceadea?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80" alt="Live Concert Audience" class="w-full h-full object-cover opacity-40">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-gray-900/60 to-gray-900/90"></div>
            </div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-10">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full border border-indigo-400/30 bg-indigo-500/10 text-indigo-300 text-sm font-bold tracking-widest uppercase mb-8 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 mr-2 animate-pulse"></span>
                    Live Music Awaits
                </span>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold mb-6 tracking-tight text-white drop-shadow-lg leading-tight">
                    Find Your Next <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Unforgettable</span> Night
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-3xl mx-auto font-medium drop-shadow leading-relaxed">
                    ConcertHub is the premier platform to discover, secure, and experience the best live music events happening near you.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="#events" class="inline-flex items-center justify-center bg-indigo-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-indigo-500 hover:scale-105 transition duration-300 shadow-xl shadow-indigo-600/40 w-full sm:w-auto">
                        Browse Events
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </a>
                    
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 w-full sm:w-auto">
                            Sign Up
                        </a>
                    @endguest
                </div>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex flex-col items-center animate-bounce text-gray-400">
                <span class="text-xs font-semibold tracking-widest uppercase mb-2">Scroll Down</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </div>
        </div>

        <!-- Featured Events -->
        <div id="events" class="pt-24 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Trending Events</h2>
                    <p class="mt-2 text-xl text-gray-500">Don't miss out on these upcoming shows.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('events.index') }}" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 cursor-pointer">
                        View all events <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

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
                <div class="text-center bg-white rounded-2xl p-12 border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-900">No events found</h3>
                    <p class="mt-2 text-gray-500">We're working on bringing more shows to your area. Check back soon!</p>
                </div>
            @endif


        </div>
</x-public-layout>
