<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.ticketType.event'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('client.dashboard', compact('orders'));
    }
}
