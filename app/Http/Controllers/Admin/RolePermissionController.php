<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('id')->get()->groupBy('module');
        $roles = Role::where('is_admin', true)->orderByRaw("FIELD(name, 'admin') DESC")->orderBy('display_name')->get();

        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->name] = $role->permissionIds();
        }

        return view('admin.roles.index', compact('permissions', 'roles', 'rolePermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_admin' => 'boolean',
        ]);

        $name = Str::slug($request->input('display_name'), '_');

        // Ensure unique name
        $baseName = $name;
        $counter = 1;
        while (Role::where('name', $name)->exists()) {
            $name = $baseName . '_' . $counter++;
        }

        Role::create([
            'name' => $name,
            'display_name' => $request->input('display_name'),
            'description' => $request->input('description'),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$request->input('display_name')}' creado correctamente.");
    }

    public function update(Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $roleName = $request->input('role');
        $role = Role::where('name', $roleName)->firstOrFail();

        // Admin always keeps all permissions
        if ($role->name === 'admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'No se pueden modificar los permisos del administrador.');
        }

        DB::table('role_has_permissions')->where('role', $roleName)->delete();

        $permissionIds = $request->input('permissions', []);
        if (!empty($permissionIds)) {
            $inserts = array_map(fn($id) => ['role' => $roleName, 'permission_id' => $id], $permissionIds);
            DB::table('role_has_permissions')->insert($inserts);
        }

        User::clearPermissionCache($roleName);

        return redirect()->route('admin.roles.index')
            ->with('success', "Permisos del rol '{$role->display_name}' actualizados correctamente.");
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_admin' => 'boolean',
        ]);

        if ($role->name === 'admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'No se puede modificar el rol de administrador.');
        }

        $role->update([
            'display_name' => $request->input('display_name'),
            'description' => $request->input('description'),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        // Clear cache for this role since is_admin may have changed
        \Illuminate\Support\Facades\Cache::forget("role_has_admin_access_{$role->name}");
        \Illuminate\Support\Facades\Cache::forget("role_is_admin_{$role->name}");

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$role->display_name}' actualizado correctamente.");
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'No se pueden eliminar roles del sistema.');
        }

        $usersCount = $role->usersCount();
        if ($usersCount > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', "No se puede eliminar el rol '{$role->display_name}' porque tiene {$usersCount} usuario(s) asignado(s).");
        }

        // Remove permissions
        DB::table('role_has_permissions')->where('role', $role->name)->delete();
        User::clearPermissionCache($role->name);

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$role->display_name}' eliminado correctamente.");
    }
}
