<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Support\Str;

class CreateRole
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(string $displayName, ?string $description, bool $isAdmin): Role
    {
        $name = Str::slug($displayName, '_');

        $baseName = $name;
        $counter = 1;
        while ($this->roleRepository->findByName($name)) {
            $name = $baseName . '_' . $counter++;
        }

        return $this->roleRepository->create([
            'name' => $name,
            'display_name' => $displayName,
            'description' => $description,
            'is_admin' => $isAdmin,
        ]);
    }
}
