<?php

namespace App\Infrastructure\Setting\Repositories;

use App\Domain\Setting\Repositories\SettingRepositoryInterface;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class EloquentSettingRepository implements SettingRepositoryInterface
{
    public function getGrouped(): Collection
    {
        return SiteSetting::orderBy('id')->get()->groupBy('group');
    }

    public function updateText(string $key, mixed $value): void
    {
        SiteSetting::where('key', $key)->update(['value' => $value]);
    }

    public function updateFile(string $key, string $path): void
    {
        $setting = SiteSetting::where('key', $key)->first();

        if (!$setting) {
            return;
        }

        // Delete old file
        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->update(['value' => $path]);
    }
}
