<?php

namespace App\Infrastructure\Role\Repositories;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }

    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role->fresh();
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }

    public function getPermissionsGrouped(): Collection
    {
        return Permission::orderBy('module')->orderBy('id')->get()->groupBy('module');
    }

    public function getAdminRoles(): Collection
    {
        return Role::where('is_admin', true)
            ->orderByRaw("FIELD(name, 'admin') DESC")
            ->orderBy('display_name')
            ->get();
    }

    public function getRolePermissionIds(): array
    {
        $roles = $this->getAdminRoles();
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->name] = $role->permissionIds();
        }
        return $rolePermissions;
    }

    public function syncPermissions(string $roleName, array $permissionIds): void
    {
        DB::table('role_has_permissions')->where('role', $roleName)->delete();

        if (!empty($permissionIds)) {
            $inserts = array_map(fn($id) => ['role' => $roleName, 'permission_id' => $id], $permissionIds);
            DB::table('role_has_permissions')->insert($inserts);
        }
    }

    public function removeAllPermissions(string $roleName): void
    {
        DB::table('role_has_permissions')->where('role', $roleName)->delete();
    }
}
