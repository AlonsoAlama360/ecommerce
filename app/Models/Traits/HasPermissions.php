<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait HasPermissions
{
    public function hasPermission(string $permission): bool
    {
        if ($this->isRoleFullAccess()) {
            return true;
        }

        $permissions = $this->rolePermissions();

        return $permissions->contains($permission);
    }

    public function isRoleFullAccess(): bool
    {
        return Cache::remember("role_is_admin_{$this->role}", 300, function () {
            $role = DB::table('roles')->where('name', $this->role)->first();
            return $role && $role->name === 'admin';
        });
    }

    public function rolePermissions(): \Illuminate\Support\Collection
    {
        return Cache::remember("role_permissions_{$this->role}", 300, function () {
            return DB::table('role_has_permissions')
                ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->where('role_has_permissions.role', $this->role)
                ->pluck('permissions.name');
        });
    }

    public static function clearPermissionCache(string $role): void
    {
        Cache::forget("role_permissions_{$role}");
        Cache::forget("role_is_admin_{$role}");
    }
}
