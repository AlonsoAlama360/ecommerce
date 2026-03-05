<?php

namespace App\Mail\Admin;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class NewReviewNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Review $review)
    {
    }

    public function envelope(): Envelope
    {
        $stars = str_repeat('★', $this->review->rating);
        return new Envelope(
            subject: "Nueva reseña {$stars} en {$this->review->product?->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-review',
        );
    }
}
