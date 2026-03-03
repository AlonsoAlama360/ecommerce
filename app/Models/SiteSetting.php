<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'label'];

    private const CACHE_KEY = 'site_settings';
    private const CACHE_TTL = 3600; // 1 hour

    public static function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, $default = null): ?string
    {
        $settings = self::allCached();
        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        self::clearCache();
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
