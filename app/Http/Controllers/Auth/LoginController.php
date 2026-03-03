<?php

namespace App\Http\Controllers\Auth;

use App\Application\User\DTOs\LoginDTO;
use App\Application\User\UseCases\LoginUser;
use App\Application\User\UseCases\LogoutUser;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private LoginUser $loginUser,
        private LogoutUser $logoutUser,
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $this->loginUser->execute(new LoginDTO(
                email: $request->email,
                password: $request->password,
                remember: $request->boolean('remember'),
            ));
        } catch (InvalidCredentialsException $e) {
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->onlyInput('email');
        }

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        $this->logoutUser->execute();

        return redirect('/');
    }
}
