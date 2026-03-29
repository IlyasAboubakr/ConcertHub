<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfs;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, array $pdfs)
    {
        $this->order = $order;
        $this->pdfs = $pdfs;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Event Tickets - Order #' . $this->order->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        foreach ($this->pdfs as $pdfData) {
            $attachments[] = Attachment::fromData(fn () => $pdfData['content'], $pdfData['name'])
                    ->withMime('application/pdf');
        }

        return $attachments;
    }
}
