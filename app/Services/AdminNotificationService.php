<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;

class AdminNotificationService
{
    public static function getNotificationEmails(): array
    {
        $raw = SiteSetting::get('notification_emails', '');
        $emails = array_filter(array_map('trim', explode(',', $raw)));

        if (empty($emails)) {
            $fallback = SiteSetting::get('contact_email');
            return $fallback ? [$fallback] : [];
        }

        return $emails;
    }

    public static function isEnabled(string $key): bool
    {
        return SiteSetting::get($key, '0') === '1';
    }

    public static function send(string $toggleKey, $mailable): void
    {
        if (!static::isEnabled($toggleKey)) {
            return;
        }

        $emails = static::getNotificationEmails();

        if (empty($emails)) {
            return;
        }

        try {
            Mail::to($emails[0])
                ->cc(array_slice($emails, 1))
                ->send($mailable);
        } catch (\Exception $e) {
            \Log::warning("AdminNotification [{$toggleKey}] failed: " . $e->getMessage());
        }
    }
}
