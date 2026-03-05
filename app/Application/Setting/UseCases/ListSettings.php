<?php

namespace App\Application\Setting\UseCases;

use App\Domain\Setting\Repositories\SettingRepositoryInterface;
use Illuminate\Support\Collection;

class ListSettings
{
    public function __construct(
        private SettingRepositoryInterface $settingRepository,
    ) {}

    public function execute(): Collection
    {
        return $this->settingRepository->getGrouped();
    }
}
