<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckoutController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'tickets' => 'required|array',
            'tickets.*' => 'integer|min:0|max:5'
        ], [
            'tickets.*.max' => 'You cannot purchase more than 5 tickets of the same type.'
        ]);

        $ticketsRequested = array_filter($request->tickets, function($qty) {
            return $qty > 0;
        });

        if (empty($ticketsRequested)) {
            return back()->with('error', 'Please select at least one ticket.');
        }

        $boughtData = \App\Models\OrderItem::whereHas('order', function($q) {
            $q->where('user_id', Auth::id())
              ->whereIn('status', ['completed', 'pending']);
        })->whereHas('ticketType', function($q) use ($event) {
            $q->where('event_id', $event->id);
        })->selectRaw('ticket_type_id, sum(quantity) as total_quantity')
          ->groupBy('ticket_type_id')
          ->get()
          ->keyBy('ticket_type_id');

        foreach ($ticketsRequested as $ticketTypeId => $quantity) {
            $bought = $boughtData->has($ticketTypeId) ? $boughtData->get($ticketTypeId)->total_quantity : 0;
            if ($bought + $quantity > 5) {
                $ticketTypeName = TicketType::find($ticketTypeId)?->name ?? 'this type';
                return back()->with('error', "You can only purchase a maximum of 5 tickets of {$ticketTypeName}. You have already purchased {$bought} tickets of this type.");
            }
        }

        $totalAmount = 0;
        $orderItemsData = [];

        DB::beginTransaction();

        try {
            foreach ($ticketsRequested as $ticketTypeId => $quantity) {
                // Lock the row for update to prevent overselling
                $ticketType = TicketType::where('id', $ticketTypeId)->where('event_id', $event->id)->lockForUpdate()->firstOrFail();

                if ($ticketType->quantity_available < $quantity) {
                    throw new \Exception("Not enough tickets available for {$ticketType->name}. Only {$ticketType->quantity_available} left.");
                }

                $totalAmount += ($ticketType->price * $quantity);

                // Decrease inventory immediately (can be released if payment fails in a real app)
                $ticketType->decrement('quantity_available', $quantity);

                for ($i = 0; $i < $quantity; $i++) {
                    $orderItemsData[] = [
                        'ticket_type_id' => $ticketType->id,
                        'quantity' => 1,
                        'price' => $ticketType->price,
                        'ticket_code' => strtoupper(Str::random(10)),
                    ];
                }
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_date' => now(),
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            // Store order ID in session for fake payment flow
            session(['checkout_order_id' => $order->id]);

            return redirect()->route('checkout.payment');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function showPayment()
    {
        $orderId = session('checkout_order_id');
        if (!$orderId) {
            return redirect()->route('home');
        }

        $order = Order::with('orderItems.ticketType.event')->findOrFail($orderId);

        return view('checkout.payment', compact('order'));
    }

    public function processPayment(Request $request)
    {
        $orderId = session('checkout_order_id');
        if (!$orderId) {
            return redirect()->route('home');
        }

        $request->validate([
            'card_holder' => 'required|string',
            'card_number' => 'required|string|size:16',
            'expiry' => 'required|string',
            'cvv' => 'required|string|size:3',
        ]);

        $order = Order::findOrFail($orderId);

        // Fake payment success
        Payment::create([
            'order_id' => $order->id,
            'card_holder' => $request->card_holder,
            'card_last_four' => substr($request->card_number, -4),
            'amount' => $order->total_amount,
            'status' => 'success',
        ]);

        $order->update(['status' => 'completed']);

        // Generate PDFs for all items
        $order->load(['orderItems.ticketType.event.organizer', 'user']);
        $pdfs = [];
        foreach ($order->orderItems as $item) {
            $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($item->ticket_code));
            $pdfContent = Pdf::loadView('tickets.pdf', ['orderItem' => $item, 'qrCode' => $qrCode])->output();
            $pdfs[] = [
                'name' => 'ticket-' . $item->ticket_code . '.pdf',
                'content' => $pdfContent
            ];
        }

        // Send email
        \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\TicketMail($order, $pdfs));

        session()->forget('checkout_order_id');

        return redirect()->route('checkout.success', $order)->with('success', 'Payment successful! Here is your order summary.');
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.ticketType.event', 'payment');
        return view('checkout.success', compact('order'));
    }

    public function downloadTicket(OrderItem $orderItem)
    {
        $orderItem->load('order.user', 'ticketType.event.organizer');

        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($orderItem->ticket_code));

        $pdf = Pdf::loadView('tickets.pdf', compact('orderItem', 'qrCode'));

        return $pdf->download("ticket-{$orderItem->ticket_code}.pdf");
    }
}
