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
        $isAsesor = $this->order->payment_method === 'asesor';

        return [
            'type' => 'new_order',
            'title' => $isAsesor ? 'Nuevo pedido' : 'Nueva venta',
            'message' => "Pedido {$this->order->order_number} — {$this->order->customer_name} — S/ " . number_format($this->order->total, 2),
            'url' => "/admin/orders?view={$this->order->id}",
            'icon' => $isAsesor ? 'fa-headset' : 'fa-shopping-bag',
            'color' => $isAsesor ? 'blue' : 'green',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $isAsesor = $this->order->payment_method === 'asesor';
        $title = $isAsesor ? 'Nuevo pedido' : 'Nueva venta recibida';
        $body = "{$this->order->customer_name} — Pedido {$this->order->order_number} por S/ " . number_format($this->order->total, 2);

        return (new WebPushMessage)
            ->title($title)
            ->icon('/images/logo_arixna1024512_min.webp')
            ->badge('/images/logo_arixna1024512_min.webp')
            ->body($body)
            ->action('Ver pedido', 'view')
            ->data(['url' => "/admin/orders?view={$this->order->id}"])
            ->options(['urgency' => 'high', 'TTL' => 86400]);
    }
}
