<?php

namespace App\Mail;

use App\Models\PpdbRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PpdbStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly PpdbRegistration $registration) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Update Status PPDB {$this->registration->registration_number} — ".(PpdbRegistration::STATUSES[$this->registration->status] ?? $this->registration->status),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb-status',
            with: [
                'registration' => $this->registration,
                'statusLabel' => PpdbRegistration::STATUSES[$this->registration->status] ?? $this->registration->status,
            ],
        );
    }
}
