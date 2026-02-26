<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->load('department', 'province', 'district');
        $departments = \App\Models\Department::orderBy('name')->get(['id', 'name']);

        return view('profile', compact('user', 'departments'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'document_type' => ['nullable', 'in:DNI,CE,RUC'],
            'document_number' => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'address_reference' => ['nullable', 'string', 'max:255'],
            'newsletter' => ['nullable'],
        ], [
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresa un email válido.',
            'email.unique' => 'Este email ya está registrado.',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->document_type = $validated['document_type'] ?? null;
        $user->document_number = $validated['document_number'] ?? null;
        $user->department_id = $validated['department_id'] ?? null;
        $user->province_id = $validated['province_id'] ?? null;
        $user->district_id = $validated['district_id'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->address_reference = $validated['address_reference'] ?? null;
        $user->newsletter = $request->has('newsletter');
        $user->save();

        return back()->with('success', 'Tus datos se actualizaron correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'current_password.required' => 'Ingresa tu contraseña actual.',
            'password.required' => 'Ingresa la nueva contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])->withInput();
        }

        $user->password = $validated['password'];
        $user->save();

        return back()->with('success', 'Tu contraseña se actualizó correctamente.');
    }
}
