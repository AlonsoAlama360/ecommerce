<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'newsletter' => 'boolean',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
            'document_type' => 'nullable|in:DNI,CE,RUC',
            'document_number' => 'nullable|string|max:20',
        ];
    }
}
