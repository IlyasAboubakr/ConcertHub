<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $organizer = Auth::user();
        
        $totalEvents = Event::where('organizer_id', $organizer->id)->count();
        $publishedEvents = Event::where('organizer_id', $organizer->id)->where('status', 'published')->count();

        $events = Event::where('organizer_id', $organizer->id)->with('ticketTypes.orderItems')->get();
        
        $totalTicketsSold = 0;
        $totalRevenue = 0;

        foreach($events as $event) {
            foreach($event->ticketTypes as $ticketType) {
                $sold = $ticketType->orderItems()->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })->sum('quantity');

                $revenue = $ticketType->orderItems()->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })->sum(\DB::raw('quantity * price'));

                $totalTicketsSold += $sold;
                $totalRevenue += $revenue;
            }
        }

        return view('organizer.dashboard', compact('totalEvents', 'publishedEvents', 'totalTicketsSold', 'totalRevenue', 'organizer'));
    }
}
