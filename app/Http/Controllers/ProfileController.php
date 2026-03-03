<?php

namespace App\Http\Controllers;

use App\Application\User\DTOs\UpdateProfileDTO;
use App\Application\User\UseCases\UpdatePassword;
use App\Application\User\UseCases\UpdateProfile;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        private UpdateProfile $updateProfile,
        private UpdatePassword $updatePassword,
    ) {}

    public function show()
    {
        $user = Auth::user();
        $user->load('department', 'province', 'district');
        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('profile', compact('user', 'departments'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->updateProfile->execute(
            Auth::user(),
            new UpdateProfileDTO(
                firstName: $request->first_name,
                lastName: $request->last_name,
                email: $request->email,
                phone: $request->phone,
                documentType: $request->document_type,
                documentNumber: $request->document_number,
                departmentId: $request->department_id ? (int) $request->department_id : null,
                provinceId: $request->province_id ? (int) $request->province_id : null,
                districtId: $request->district_id ? (int) $request->district_id : null,
                address: $request->address,
                addressReference: $request->address_reference,
                newsletter: $request->has('newsletter'),
            ),
        );

        return back()->with('success', 'Tus datos se actualizaron correctamente.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $this->updatePassword->execute(
                Auth::user(),
                $request->current_password,
                $request->password,
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['current_password' => $e->getMessage()])->withInput();
        }

        return back()->with('success', 'Tu contraseña se actualizó correctamente.');
    }
}
