<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class UpdateRole
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(Role $role, string $displayName, ?string $description, bool $isAdmin): string|Role
    {
        if ($role->name === 'admin') {
            return 'No se puede modificar el rol de administrador.';
        }

        $updated = $this->roleRepository->update($role, [
            'display_name' => $displayName,
            'description' => $description,
            'is_admin' => $isAdmin,
        ]);

        Cache::forget("role_has_admin_access_{$role->name}");
        Cache::forget("role_is_admin_{$role->name}");

        return $updated;
    }
}
