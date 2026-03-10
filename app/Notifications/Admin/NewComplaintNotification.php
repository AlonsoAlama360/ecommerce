<?php

namespace App\Notifications\Admin;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewComplaintNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Complaint $complaint) {}

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
            'type' => 'new_complaint',
            'title' => 'Nuevo reclamo',
            'message' => "#{$this->complaint->complaint_number} — {$this->complaint->consumer_name}",
            'url' => "/admin/complaints/{$this->complaint->id}",
            'icon' => 'fa-exclamation-triangle',
            'color' => 'red',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Nuevo reclamo registrado')
            ->icon('/images/logo_arixna.png')
            ->badge('/images/logo_arixna.png')
            ->body("#{$this->complaint->complaint_number} — {$this->complaint->consumer_name}")
            ->data(['url' => "/admin/complaints/{$this->complaint->id}"])
            ->options(['urgency' => 'high', 'TTL' => 86400]);
    }
}
