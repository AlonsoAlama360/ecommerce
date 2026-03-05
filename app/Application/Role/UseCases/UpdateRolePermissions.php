<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Models\User;

class UpdateRolePermissions
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(string $roleName, array $permissionIds): string|true
    {
        $role = $this->roleRepository->findByName($roleName);

        if (!$role) {
            return 'Rol no encontrado.';
        }

        if ($role->name === 'admin') {
            return 'No se pueden modificar los permisos del administrador.';
        }

        $this->roleRepository->syncPermissions($roleName, $permissionIds);
        User::clearPermissionCache($roleName);

        return true;
    }
}
