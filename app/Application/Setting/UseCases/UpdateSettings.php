<?php

namespace App\Application\Setting\UseCases;

use App\Domain\Setting\Repositories\SettingRepositoryInterface;
use App\Models\SiteSetting;

class UpdateSettings
{
    public function __construct(
        private SettingRepositoryInterface $settingRepository,
    ) {}

    public function execute(array $textSettings, array $fileSettings): void
    {
        // Handle file uploads
        foreach ($fileSettings as $key => $file) {
            $path = $file->store('settings', 'public');
            $this->settingRepository->updateFile($key, $path);
        }

        // Handle text settings
        foreach ($textSettings as $key => $value) {
            $this->settingRepository->updateText($key, $value);
        }

        SiteSetting::clearCache();
    }
}
