<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->route('user'))],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'newsletter' => 'boolean',
            'role' => 'required|in:admin,vendedor,cliente',
            'is_active' => 'boolean',
            'document_type' => 'nullable|in:DNI,CE,RUC',
            'document_number' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'address' => 'nullable|string|max:255',
            'address_reference' => 'nullable|string|max:255',
        ];
    }
}
