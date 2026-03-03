<?php

namespace App\Http\Controllers\Auth;

use App\Application\User\DTOs\RegisterUserDTO;
use App\Application\User\UseCases\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;

class RegisterController extends Controller
{
    public function __construct(
        private RegisterUser $registerUser,
    ) {}

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->registerUser->execute(new RegisterUserDTO(
            firstName: $request->first_name,
            lastName: $request->last_name,
            email: $request->email,
            password: $request->password,
            newsletter: $request->boolean('newsletter'),
        ));

        return redirect('/')->with('success', '¡Bienvenido a Arixna!');
    }
}
