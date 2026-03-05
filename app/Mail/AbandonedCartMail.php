<?php

namespace App\Mail;

use App\Models\AbandonedCart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class AbandonedCartMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $products;

    public function __construct(public AbandonedCart $abandonedCart, array $products)
    {
        $this->products = $products;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Olvidaste algo en tu carrito - Arixna",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-cart',
        );
    }
}
