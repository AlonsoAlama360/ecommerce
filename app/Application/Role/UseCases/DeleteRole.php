<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Models\Role;
use App\Models\User;

class DeleteRole
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(Role $role): string|true
    {
        if ($role->is_system) {
            return 'No se pueden eliminar roles del sistema.';
        }

        $usersCount = $role->usersCount();
        if ($usersCount > 0) {
            return "No se puede eliminar el rol '{$role->display_name}' porque tiene {$usersCount} usuario(s) asignado(s).";
        }

        $this->roleRepository->removeAllPermissions($role->name);
        User::clearPermissionCache($role->name);
        $this->roleRepository->delete($role);

        return true;
    }
}
