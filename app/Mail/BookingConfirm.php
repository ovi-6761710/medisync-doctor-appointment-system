<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirm extends Mailable
{
    use Queueable, SerializesModels;

    private $booking = null;
    private $to_type = "";

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $to_type)
    {
        $this->booking = $booking;
        $this->to_type = $to_type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $doctor = $this->booking->doctor ?? null;

        return new Content(
            view: "mails/booking-confirm",
            with: [
                "booking" => $this->booking,
                "doctor" => $doctor,
                "to" => $this->to_type
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
