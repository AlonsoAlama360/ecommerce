<?php

namespace App\Notifications\Admin;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

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
            'type' => 'new_order',
            'title' => 'Nueva venta',
            'message' => "Pedido {$this->order->order_number} — S/ " . number_format($this->order->total, 2),
            'url' => "/admin/orders?view={$this->order->id}",
            'icon' => 'fa-shopping-bag',
            'color' => 'green',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Nueva venta recibida')
            ->icon('/images/logo_arixna.png')
            ->badge('/images/logo_arixna.png')
            ->body("Pedido {$this->order->order_number} por S/ " . number_format($this->order->total, 2))
            ->action('Ver pedido', 'view')
            ->data(['url' => "/admin/orders?view={$this->order->id}"])
            ->options(['urgency' => 'high', 'TTL' => 86400]);
    }
}
