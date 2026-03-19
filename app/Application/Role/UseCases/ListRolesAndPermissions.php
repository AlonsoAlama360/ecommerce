<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;

class ListRolesAndPermissions
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(): array
    {
        return [
            'permissions' => $this->roleRepository->getPermissionsGrouped(),
            'roles' => $this->roleRepository->getAdminRoles(),
            'rolePermissions' => $this->roleRepository->getRolePermissionIds(),
            'roleNotificationTypes' => $this->roleRepository->getRoleNotificationTypes(),
        ];
    }
}
