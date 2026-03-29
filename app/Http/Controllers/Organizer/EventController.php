<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', Auth::id())->orderBy('event_date', 'asc')->paginate(15);
        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        return view('organizer.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after:today'],
            'event_time' => ['required'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published'],
            'tickets' => ['required', 'array', 'min:1'],
            'tickets.*.name' => ['required', 'string', 'max:255'],
            'tickets.*.price' => ['required', 'numeric', 'min:0'],
            'tickets.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['organizer_id'] = Auth::id();
        // Since the Organizer creates the event, we can default the artist_name to the organizer's artist_name if not provided
        $validated['artist_name'] = Auth::user()->artist_name;

        $event = Event::create($validated);

        // Process tickets
        foreach ($request->tickets as $ticketData) {
            $event->ticketTypes()->create([
                'name' => $ticketData['name'],
                'price' => $ticketData['price'],
                'quantity_available' => $ticketData['quantity'],
            ]);
        }

        return redirect()->route('organizer.events.index')->with('success', 'Event and tickets created successfully.');
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);
        $event->load('ticketTypes.orderItems');
        
        $totalSold = \App\Models\OrderItem::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->whereHas('ticketType', function($q) use ($event) {
            $q->where('event_id', $event->id);
        })->sum('quantity');

        $totalRevenue = \App\Models\OrderItem::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->whereHas('ticketType', function($q) use ($event) {
            $q->where('event_id', $event->id);
        })->sum(\Illuminate\Support\Facades\DB::raw('quantity * price'));

        return view('organizer.events.show', compact('event', 'totalSold', 'totalRevenue'));
    }

    public function exportAudit(Event $event)
    {
        $this->authorize('view', $event);
        
        $orderItems = \App\Models\OrderItem::with(['order.user', 'ticketType'])
            ->whereHas('ticketType', function($q) use ($event) {
                $q->where('event_id', $event->id);
            })
            ->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })->get();

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() use ($orderItems, $event) {
            $handle = fopen('php://output', 'w');
            
            $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            $html .= '<head><meta charset="UTF-8"></head>';
            $html .= '<body><h2>Audience Audit - ' . htmlspecialchars($event->title) . '</h2><table border="1">';
            
            $html .= '<thead><tr>';
            $headers = ['Client Name', 'Client Email', 'Ticket Type', 'Ticket Code', 'Price ($)', 'Quantity', 'Purchase Date'];
            foreach ($headers as $header) {
                $html .= '<th style="background-color: #B4C6E7; color: #000000; font-weight: bold;">' . $header . '</th>';
            }
            $html .= '</tr></thead>';
            
            $html .= '<tbody>';
            foreach ($orderItems as $item) {
                $client = $item->order?->user;
                
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($client ? $client->name : 'Unknown Client') . '</td>';
                $html .= '<td>' . htmlspecialchars($client ? $client->email : 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars($item->ticketType->name ?? 'Unknown Type') . '</td>';
                $html .= '<td>' . htmlspecialchars($item->ticket_code) . '</td>';
                $html .= '<td>' . htmlspecialchars(number_format($item->price, 2)) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->quantity) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->created_at->format('Y-m-d H:i:s')) . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table></body></html>';

            fwrite($handle, $html);
            fclose($handle);
        });

        $cleanTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $event->title);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="audit_' . $cleanTitle . '_' . date('Y-m-d') . '.xls"');

        return $response;
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('organizer.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'event_time' => ['required'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published'],
        ]);

        if ($request->hasFile('image')) {
            // Delete old
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('organizer.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        // Maybe soft delete later, for now hard delete or change status
        $event->delete();
        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully.');
    }
}
