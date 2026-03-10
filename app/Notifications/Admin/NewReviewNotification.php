<?php

namespace App\Notifications\Admin;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Review $review) {}

    public function via($notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->pushSubscriptions()->exists()) {
            $channels[] = WebPushChannel::class;
        }
        return $channels;
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'new_review',
            'title' => 'Nueva reseña',
            'message' => "\"{$this->review->title}\" — {$this->review->rating} estrellas",
            'url' => '/admin/reviews',
            'icon' => 'fa-star',
            'color' => 'yellow',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Nueva reseña recibida')
            ->icon('/images/logo_arixna.png')
            ->badge('/images/logo_arixna.png')
            ->body("\"{$this->review->title}\" — {$this->review->rating} estrellas")
            ->data(['url' => '/admin/reviews'])
            ->options(['urgency' => 'high', 'TTL' => 86400]);
    }
}
