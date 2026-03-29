<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('organizer')->orderBy('event_date', 'asc')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['organizer', 'ticketTypes.orderItems']);
        
        $totalTicketsSold = 0;
        $totalRevenue = 0;

        foreach($event->ticketTypes as $ticketType) {
            $sold = $ticketType->orderItems()->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })->sum('quantity');

            $revenue = $ticketType->orderItems()->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })->sum(\DB::raw('quantity * price'));

            $ticketType->sold_count = $sold;
            $ticketType->revenue_generated = $revenue;

            $totalTicketsSold += $sold;
            $totalRevenue += $revenue;
        }

        return view('admin.events.show', compact('event', 'totalTicketsSold', 'totalRevenue'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'image|max:2048']);
            if ($event->image && !str_starts_with($event->image, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->image && !str_starts_with($event->image, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->image);
        }
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
