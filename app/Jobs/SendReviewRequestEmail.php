<?php

namespace App\Jobs;

use App\Mail\ReviewRequestMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReviewRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function handle(): void
    {
        $this->order->refresh();

        if ($this->order->review_requested_at) {
            return;
        }

        if ($this->order->status !== 'entregado') {
            return;
        }

        $this->order->load('items.product');
        Mail::to($this->order->customer_email)->send(new ReviewRequestMail($this->order));

        $this->order->update(['review_requested_at' => now()]);
    }
}
