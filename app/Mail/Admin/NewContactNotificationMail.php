<?php

namespace App\Mail\Admin;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nuevo mensaje de contacto: {$this->contact->subject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-contact',
        );
    }
}
