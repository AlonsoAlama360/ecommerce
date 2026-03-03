<?php

namespace App\Http\Controllers\Admin;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UserFiltersDTO;
use App\Application\User\UseCases\CreateUser;
use App\Application\User\UseCases\DeleteUser;
use App\Application\User\UseCases\ListUsers;
use App\Application\User\UseCases\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AdminCreateUserRequest;
use App\Http\Requests\User\AdminUpdateUserRequest;
use App\Models\Department;
use App\Models\User;

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

        return view('admin.users.index', array_merge($data, compact('departments')));
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
            departmentId: $request->department_id ? (int) $request->department_id : null,
            provinceId: $request->province_id ? (int) $request->province_id : null,
            districtId: $request->district_id ? (int) $request->district_id : null,
            address: $request->address,
            addressReference: $request->address_reference,
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
        $this->updateUser->execute($user, new CreateUserDTO(
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
            departmentId: $request->department_id ? (int) $request->department_id : null,
            provinceId: $request->province_id ? (int) $request->province_id : null,
            districtId: $request->district_id ? (int) $request->district_id : null,
            address: $request->address,
            addressReference: $request->address_reference,
        ));

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
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
