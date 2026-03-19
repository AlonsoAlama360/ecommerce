<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminNotificationService
{
    public static function notify(string $type, Notification $notification): void
    {
        try {
            $roles = DB::table('role_notification_types')
                ->where('notification_type', $type)
                ->pluck('role')
                ->toArray();

            if (empty($roles)) {
                return;
            }

            $admins = User::whereIn('role', $roles)->where('is_active', true)->get();
            foreach ($admins as $admin) {
                $admin->notify($notification);
            }
        } catch (\Exception $e) {
            \Log::warning("AdminNotification push failed: " . $e->getMessage());
        }
    }

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
                ->queue($mailable);
        } catch (\Exception $e) {
            \Log::warning("AdminNotification [{$toggleKey}] failed: " . $e->getMessage());
        }
    }
}
