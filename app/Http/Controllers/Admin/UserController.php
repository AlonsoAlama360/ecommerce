<?php

namespace App\Http\Controllers\Admin;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Application\User\DTOs\UserFiltersDTO;
use App\Application\User\UseCases\CreateUser;
use App\Application\User\UseCases\DeleteUser;
use App\Application\User\UseCases\ListUsers;
use App\Application\User\UseCases\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AdminCreateUserRequest;
use App\Http\Requests\User\AdminUpdateUserRequest;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private ListUsers $listUsers,
        private CreateUser $createUser,
        private UpdateUser $updateUser,
        private DeleteUser $deleteUser,
    ) {}

    public function index(\Illuminate\Http\Request $request)
    {
        $data = $this->listUsers->execute(new UserFiltersDTO(
            search: $request->get('search'),
            role: $request->get('role'),
            status: $request->has('status') && $request->get('status') !== '' ? $request->get('status') : null,
            dateFrom: $request->get('date_from'),
            dateTo: $request->get('date_to'),
            perPage: (int) $request->get('per_page', 10),
        ));

        $departments = Department::orderBy('name')->get(['id', 'name']);
        $roles = Role::orderBy('display_name')->get(['name', 'display_name']);

        return view('admin.users.index', array_merge($data, compact('departments', 'roles')));
    }

    public function create()
    {
        return redirect()->route('admin.users.index');
    }

    public function store(AdminCreateUserRequest $request)
    {
        $this->createUser->execute(new CreateUserDTO(
            firstName: $request->first_name,
            lastName: $request->last_name,
            email: $request->email,
            password: $request->password,
            role: $request->role,
            isActive: $request->boolean('is_active'),
            newsletter: $request->boolean('newsletter'),
            phone: $request->phone,
            documentType: $request->document_type,
            documentNumber: $request->document_number,
        ));

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        return redirect()->route('admin.users.index');
    }

    public function update(AdminUpdateUserRequest $request, User $user)
    {
        $this->updateUser->execute($user, new UpdateUserDTO(
            firstName: $request->first_name,
            lastName: $request->last_name,
            email: $request->email,
            password: $request->password,
            role: $request->role,
            isActive: $request->boolean('is_active'),
            newsletter: $request->boolean('newsletter'),
            phone: $request->phone,
            documentType: $request->document_type,
            documentNumber: $request->document_number,
        ));

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function updateAddress(Request $request, User $user)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'address' => 'nullable|string|max:255',
            'address_reference' => 'nullable|string|max:255',
        ]);

        $user->update([
            'department_id' => $validated['department_id'] ?: null,
            'province_id' => $validated['province_id'] ?: null,
            'district_id' => $validated['district_id'] ?: null,
            'address' => $validated['address'] ?: null,
            'address_reference' => $validated['address_reference'] ?: null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Dirección actualizada exitosamente.');
    }

    public function destroyAddress(User $user)
    {
        $user->update([
            'department_id' => null,
            'province_id' => null,
            'district_id' => null,
            'address' => null,
            'address_reference' => null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Dirección eliminada exitosamente.');
    }

    public function destroy(User $user)
    {
        try {
            $this->deleteUser->execute($user, auth()->id());
        } catch (\LogicException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
