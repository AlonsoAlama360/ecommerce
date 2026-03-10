<?php

namespace App\Notifications\Admin;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewContactNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactMessage $contact) {}

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
            'type' => 'new_contact',
            'title' => 'Nuevo mensaje de contacto',
            'message' => "De {$this->contact->name}: \"{$this->contact->subject}\"",
            'url' => '/admin/contact-messages',
            'icon' => 'fa-envelope',
            'color' => 'blue',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Nuevo mensaje de contacto')
            ->icon('/images/logo_arixna.png')
            ->badge('/images/logo_arixna.png')
            ->body("De {$this->contact->name}: \"{$this->contact->subject}\"")
            ->data(['url' => '/admin/contact-messages'])
            ->options(['urgency' => 'high', 'TTL' => 86400]);
    }
}
