<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Facades\Auth;

class TicketTypeController extends Controller
{
    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('organizer.tickets.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity_available' => ['required', 'integer', 'min:1'],
        ]);

        $event->ticketTypes()->create($validated);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket Type added successfully.');
    }

    public function edit(Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);
        return view('organizer.tickets.edit', compact('event', 'ticketType'));
    }

    public function update(Request $request, Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity_available' => ['required', 'integer', 'min:0'],
        ]);

        $ticketType->update($validated);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket Type updated successfully.');
    }

    public function destroy(Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);
        
        // Cannot delete if there are orders
        if ($ticketType->orderItems()->count() > 0) {
            return redirect()->route('organizer.events.show', $event)->with('error', 'Cannot delete ticket type because it has sales.');
        }

        $ticketType->delete();

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket Type deleted successfully.');
    }
}
