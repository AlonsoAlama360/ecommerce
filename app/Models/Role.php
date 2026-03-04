<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description', 'is_admin'];

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function permissions()
    {
        return DB::table('role_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('role_has_permissions.role', $this->name)
            ->select('permissions.*');
    }

    public function permissionIds(): array
    {
        return DB::table('role_has_permissions')
            ->where('role', $this->name)
            ->pluck('permission_id')
            ->toArray();
    }

    public function usersCount(): int
    {
        return \App\Models\User::where('role', $this->name)->count();
    }
}
