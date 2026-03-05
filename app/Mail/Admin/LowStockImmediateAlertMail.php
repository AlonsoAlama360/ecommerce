<?php

namespace App\Mail\Admin;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class LowStockImmediateAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public int $threshold,
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->product->stock === 0 ? 'SIN STOCK' : "stock bajo ({$this->product->stock})";
        return new Envelope(
            subject: "[Alerta] {$this->product->name} - {$label}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.low-stock-immediate',
        );
    }
}
