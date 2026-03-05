<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscribedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $email)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido/a al newsletter de Arixna! 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-subscribed',
        );
    }
}
