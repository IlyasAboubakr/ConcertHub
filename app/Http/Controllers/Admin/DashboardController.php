<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'client')->count();
        $totalOrganizers = User::where('role', 'organizer')->count();
        
        $totalTicketsSold = OrderItem::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->sum('quantity');

        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $commission = $totalRevenue * 0.02;

        // Tickets sold per organizer
        $organizerStats = User::where('role', 'organizer')->with(['events.ticketTypes.orderItems' => function($q) {
            $q->whereHas('order', function($q2) {
                $q2->where('status', 'completed');
            });
        }])->get()->map(function($organizer) {
            $sold = 0;
            foreach($organizer->events as $event) {
                foreach($event->ticketTypes as $tt) {
                    $sold += $tt->orderItems->sum('quantity');
                }
            }
            return [
                'name' => $organizer->name,
                'artist_name' => $organizer->artist_name,
                'tickets_sold' => $sold
            ];
        })->sortByDesc('tickets_sold')->values();

        $topOrganizers = $organizerStats->take(3);

        return view('admin.dashboard', compact('totalUsers', 'totalOrganizers', 'totalTicketsSold', 'totalRevenue', 'commission', 'topOrganizers'));
    }

    public function organizersStats()
    {
        $organizerStats = User::where('role', 'organizer')->with(['events.ticketTypes.orderItems' => function($q) {
            $q->whereHas('order', function($q2) {
                $q2->where('status', 'completed');
            });
        }])->get()->map(function($organizer) {
            $sold = 0;
            foreach($organizer->events as $event) {
                foreach($event->ticketTypes as $tt) {
                    $sold += $tt->orderItems->sum('quantity');
                }
            }
            return [
                'name' => $organizer->name,
                'artist_name' => $organizer->artist_name,
                'tickets_sold' => $sold
            ];
        })->sortByDesc('tickets_sold')->values();

        return view('admin.organizers.stats', compact('organizerStats'));
    }
    public function exportTickets()
    {
        $orderItems = OrderItem::with([
            'order.user', 
            'ticketType.event.organizer'
        ])->whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->get();

        $response = new StreamedResponse(function() use ($orderItems) {
            $handle = fopen('php://output', 'w');
            
            // Output simple HTML table for Excel
            $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            $html .= '<head><meta charset="UTF-8"></head>';
            $html .= '<body><table border="1">';
            
            // Header row with colors
            $html .= '<thead><tr>';
            $headers = ['Organizer Name', 'Event Title', 'Client Name', 'Client Email', 'Ticket Type', 'Ticket Code', 'Price ($)', 'Quantity', 'Purchase Date'];
            foreach ($headers as $header) {
                // Let\'s use a light blue background for headers
                $html .= '<th style="background-color: #B4C6E7; color: #000000; font-weight: bold;">' . $header . '</th>';
            }
            $html .= '</tr></thead>';
            
            $html .= '<tbody>';
            foreach ($orderItems as $item) {
                $organizer = $item->ticketType->event?->organizer;
                $client = $item->order?->user;
                
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($organizer ? $organizer->name : 'Unknown Organizer') . '</td>';
                $html .= '<td>' . htmlspecialchars($item->ticketType->event?->title ?? 'Unknown Event') . '</td>';
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

        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="tickets_audit_'.date('Y-m-d_H-i-s').'.xls"');

        return $response;
    }
}
