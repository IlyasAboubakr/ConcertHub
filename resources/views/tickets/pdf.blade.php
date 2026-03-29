<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ConcertHub Ticket - {{ $orderItem->ticket_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            text-align: center; /* Center horizontally at body level */
        }
        .ticket {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
            position: relative;
            left: -30px; /* Shift the entire block to the left */
        }
        .header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0 0 10px 0;
            font-size: 32px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .details {
            margin-bottom: 40px;
        }
        h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .org {
            color: #666;
            margin-bottom: 30px;
            font-size: 18px;
        }
        .data-grid {
            margin: 0 auto 30px auto;
            width: 100%;
            max-width: 600px;
        }
        .data-row {
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
            color: #777;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .value {
            font-size: 20px;
        }
        .qr-section {
            margin-top: 40px;
        }
        .qr-image {
            margin: 0 auto;
        }
        .code-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            margin: 30px auto 0 auto;
            border-radius: 4px;
            max-width: 300px;
        }
        .ticket-code {
            font-family: monospace;
            font-size: 24px;
            letter-spacing: 5px;
            font-weight: bold;
            color: #4f46e5;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="ticket">
        
        <div class="header">
            <h1>ConcertHub</h1>
            <p>Official Event Ticket</p>
        </div>

        <div class="details">
            <h2>{{ $orderItem->ticketType->event?->title ?? 'Unknown Event' }}</h2>
            <div class="org">Presented by {{ $orderItem->ticketType->event?->organizer?->artist_name ?? $orderItem->ticketType->event?->organizer?->name ?? 'Unknown Organizer' }}</div>

            <div class="data-grid">
                <div class="data-row">
                    <div class="label">Admit One</div>
                    <div class="value"><strong>{{ $orderItem->order->user?->name ?? 'Unknown User' }}</strong></div>
                </div>

                <div class="data-row">
                    <div class="label">Ticket Type</div>
                    <div class="value">{{ $orderItem->ticketType->name }}</div>
                </div>

                <div class="data-row">
                    <div class="label">Date & Time</div>
                    <div class="value">
                        {{ $orderItem->ticketType->event->event_date->format('l, F j, Y') }} at 
                        {{ date('g:i A', strtotime($orderItem->ticketType->event->event_time)) }}
                    </div>
                </div>

                <div class="data-row">
                    <div class="label">Location</div>
                    <div class="value">{{ $orderItem->ticketType->event->location }}</div>
                </div>
            </div>
        </div>

        <div class="qr-section">
            <div class="qr-image">
                <img src="data:image/svg+xml;base64,{!! $qrCode !!}" alt="QR Code" width="200" height="200">
            </div>
            
            <div class="code-box">
                <div class="label">Ticket Code</div>
                <div class="ticket-code">{{ $orderItem->ticket_code }}</div>
            </div>
        </div>

        <div class="footer">
            This ticket is unique and generated for a single use at entry.<br>
            Order #{{ $orderItem->order_id }} &bull; Purchased {{ $orderItem->created_at->format('M d, Y') }}
        </div>

    </div>

</body>
</html>
