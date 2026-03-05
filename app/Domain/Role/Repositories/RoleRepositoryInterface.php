<?php

namespace App\Domain\Role\Repositories;

use App\Models\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function findByName(string $name): ?Role;

    public function findById(int $id): ?Role;

    public function create(array $data): Role;

    public function update(Role $role, array $data): Role;

    public function delete(Role $role): void;

    public function getPermissionsGrouped(): Collection;

    public function getAdminRoles(): Collection;

    public function getRolePermissionIds(): array;

    public function syncPermissions(string $roleName, array $permissionIds): void;

    public function removeAllPermissions(string $roleName): void;
}
