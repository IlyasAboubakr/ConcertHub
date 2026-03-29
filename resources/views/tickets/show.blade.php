<x-public-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    
                    <div class="mb-8 pb-8 border-b border-gray-100 flex items-center gap-6">
                        @if($event->image)
                            <img src="{{ \Illuminate\Support\Str::startsWith($event->image, 'http') ? $event->image : Storage::url($event->image) }}" class="w-32 h-32 object-cover rounded-lg shadow-sm" alt="{{ $event->title }}">
                        @else
                            <div class="w-32 h-32 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm12 0c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"></path></svg>
                            </div>
                        @endif
                        
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $event->title }}</h1>
                            <div class="text-gray-600 flex items-center mb-1">
                                <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $event->event_date->format('l, F j, Y') }} at {{ date('g:i A', strtotime($event->event_time)) }}
                            </div>
                            <div class="text-gray-600 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $event->location }}
                            </div>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h3 class="text-2xl font-bold mb-6">Select Your Tickets</h3>
                    
                    @if(auth()->user()->role !== 'client')
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-lg mb-6">
                            <strong>Notice:</strong> You are currently logged in as an <strong>{{ ucfirst(auth()->user()->role) }}</strong>. 
                            Only Client accounts can purchase tickets. Please log out and sign in with a client account to test the purchase flow.
                        </div>
                    @endif

                    @if($event->ticketTypes->isEmpty())
                        <div class="bg-gray-50 text-gray-500 p-6 rounded-lg text-center border">
                            Tickets are not available for this event yet.
                        </div>
                    @else
                        <form action="{{ route('checkout.store', $event) }}" method="POST" class="space-y-4">
                            @csrf
                            @foreach($event->ticketTypes as $ticket)
                                <div class="border rounded-xl p-5 {{ $ticket->quantity_available > 0 ? 'bg-white hover:border-indigo-300 transition shadow-sm' : 'bg-gray-50 opacity-60' }}">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-xl text-gray-900">{{ $ticket->name }}</h4>
                                            <p class="text-indigo-600 font-extrabold text-lg">${{ number_format($ticket->price, 2) }}</p>
                                        </div>
                                        
                                        @php
                                            $bought = isset($ticketsBoughtPerType) ? ($ticketsBoughtPerType[$ticket->id] ?? 0) : 0;
                                            $maxAllowed = max(0, 5 - $bought);
                                        @endphp
                                        
                                        @if($ticket->quantity_available > 0 && $maxAllowed > 0)
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm text-gray-500">{{ $ticket->quantity_available }} remaining</span>
                                                <select name="tickets[{{ $ticket->id }}]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-bold text-lg py-2 pl-4 pr-10" {{ auth()->user()->role !== 'client' ? 'disabled' : '' }}>
                                                    <option value="0">0</option>
                                                    @php $currentMax = min($maxAllowed, $ticket->quantity_available); @endphp
                                                    @for($i = 1; $i <= $currentMax; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        @elseif($maxAllowed <= 0)
                                            <span class="text-blue-600 font-bold uppercase tracking-wider text-sm px-3 py-1 bg-blue-50 rounded-lg border border-blue-200">Max Reached (5 limit)</span>
                                        @else
                                            <span class="text-red-500 font-bold uppercase tracking-wider text-sm px-3 py-1 bg-red-50 rounded-lg border border-red-100">Sold Out</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="pt-8 flex justify-between items-center">
                                <a href="{{ route('events.show', $event) }}" class="text-gray-500 hover:text-gray-900 font-medium transition">
                                    &larr; Back to Event
                                </a>
                                @if(auth()->user()->role === 'client')
                                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg shadow-indigo-600/30 flex items-center">
                                        Continue to Payment
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                @endif
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-public-layout>
