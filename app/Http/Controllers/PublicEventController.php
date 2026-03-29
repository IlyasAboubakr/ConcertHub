<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class PublicEventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->with('organizer')
            ->take(3)
            ->get();
            
        return view('welcome', compact('events'));
    }

    public function allEvents(Request $request)
    {
        $query = Event::where('status', 'published')
            ->with('organizer');

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('date')) {
            $query->where('event_date', $request->date);
        } else {
            $query->where('event_date', '>=', now()->toDateString());
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(12)->withQueryString();
        
        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $event->load(['organizer', 'ticketTypes']);
        return view('events.show', compact('event'));
    }
}
