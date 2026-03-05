<?php

namespace App\Domain\Setting\Repositories;

use Illuminate\Support\Collection;

interface SettingRepositoryInterface
{
    public function getGrouped(): Collection;

    public function updateText(string $key, mixed $value): void;

    public function updateFile(string $key, string $path): void;
}
