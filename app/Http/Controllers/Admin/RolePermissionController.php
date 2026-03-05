<?php

namespace App\Http\Controllers\Admin;

use App\Application\Role\UseCases\CreateRole;
use App\Application\Role\UseCases\DeleteRole;
use App\Application\Role\UseCases\ListRolesAndPermissions;
use App\Application\Role\UseCases\UpdateRole;
use App\Application\Role\UseCases\UpdateRolePermissions;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index(ListRolesAndPermissions $listRoles)
    {
        $data = $listRoles->execute();

        return view('admin.roles.index', $data);
    }

    public function store(Request $request, CreateRole $createRole)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_admin' => 'boolean',
        ]);

        $role = $createRole->execute(
            $request->input('display_name'),
            $request->input('description'),
            $request->boolean('is_admin'),
        );

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$role->display_name}' creado correctamente.");
    }

    public function update(Request $request, UpdateRolePermissions $updatePermissions)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $result = $updatePermissions->execute(
            $request->input('role'),
            $request->input('permissions', []),
        );

        if (is_string($result)) {
            return redirect()->route('admin.roles.index')->with('error', $result);
        }

        $role = Role::where('name', $request->input('role'))->first();
        return redirect()->route('admin.roles.index')
            ->with('success', "Permisos del rol '{$role->display_name}' actualizados correctamente.");
    }

    public function updateRole(Request $request, Role $role, UpdateRole $updateRole)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_admin' => 'boolean',
        ]);

        $result = $updateRole->execute(
            $role,
            $request->input('display_name'),
            $request->input('description'),
            $request->boolean('is_admin'),
        );

        if (is_string($result)) {
            return redirect()->route('admin.roles.index')->with('error', $result);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol '{$result->display_name}' actualizado correctamente.");
    }

    public function destroy(Role $role, DeleteRole $deleteRole)
    {
        $result = $deleteRole->execute($role);

        if (is_string($result)) {
            return redirect()->route('admin.roles.index')->with('error', $result);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Rol eliminado correctamente.");
    }
}
