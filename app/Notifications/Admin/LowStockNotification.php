<?php

namespace App\Notifications\Admin;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Product $product) {}

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
        $msg = $this->product->stock === 0
            ? "\"{$this->product->name}\" se agotó"
            : "\"{$this->product->name}\" tiene solo {$this->product->stock} unidades";

        return [
            'type' => 'low_stock',
            'title' => 'Stock bajo',
            'message' => $msg,
            'url' => "/admin/products/{$this->product->id}/edit",
            'icon' => 'fa-box',
            'color' => 'orange',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $msg = $this->product->stock === 0
            ? "\"{$this->product->name}\" se agotó"
            : "\"{$this->product->name}\" tiene solo {$this->product->stock} unidades";

        return (new WebPushMessage)
            ->title('Alerta de stock')
            ->icon('/images/logo_arixna.png')
            ->badge('/images/logo_arixna.png')
            ->body($msg)
            ->data(['url' => "/admin/products/{$this->product->id}/edit"])
            ->options(['urgency' => 'high', 'TTL' => 86400]);
    }
}
