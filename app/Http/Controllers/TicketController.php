<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class TicketController extends Controller
{
    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $event->load(['organizer', 'ticketTypes']);
        
        $ticketsBoughtPerType = [];
        if (auth()->check() && auth()->user()->role === 'client') {
            $boughtData = \App\Models\OrderItem::whereHas('order', function($q) {
                $q->where('user_id', auth()->id())
                  ->whereIn('status', ['completed', 'pending']);
            })->whereHas('ticketType', function($q) use ($event) {
                $q->where('event_id', $event->id);
            })->selectRaw('ticket_type_id, sum(quantity) as total_quantity')
              ->groupBy('ticket_type_id')
              ->get()
              ->keyBy('ticket_type_id');

            foreach ($event->ticketTypes as $tt) {
                $ticketsBoughtPerType[$tt->id] = $boughtData->has($tt->id) ? $boughtData->get($tt->id)->total_quantity : 0;
            }
        }
        
        return view('tickets.show', compact('event', 'ticketsBoughtPerType'));
    }
}
