<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $requestRecord;
    public $status;
    public $checkedByEmail;
    public $approvedByEmail;
    /**
     * Create a new message instance.
     */
    public function __construct($requestRecord, $status, $checkedByEmail = null, $approvedByEmail = null)
    {
        $this->requestRecord = $requestRecord;
        $this->status = $status;
        $this->checkedByEmail = $checkedByEmail;
        $this->approvedByEmail = $approvedByEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address("info@vallibelone.com", "Vallibel One"),
            subject: "Request #{$this->requestRecord->id} Status Updated: ".ucfirst($this->status),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.status-notification',
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
