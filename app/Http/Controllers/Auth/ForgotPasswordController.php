<?php

namespace App\Http\Controllers\Auth;

use App\Application\User\DTOs\ResetPasswordDTO;
use App\Application\User\UseCases\ResetPassword;
use App\Application\User\UseCases\SendPasswordReset;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResetPasswordRequest;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private SendPasswordReset $sendPasswordReset,
        private ResetPassword $resetPassword,
    ) {}

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresa un email válido.',
        ]);

        $this->sendPasswordReset->execute($request->email);

        return back()->with('status', 'Si el email está registrado, recibirás un enlace de recuperación.');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $this->resetPassword->execute(new ResetPasswordDTO(
                token: $request->token,
                email: $request->email,
                password: $request->password,
            ));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }

        return redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente. Ya puedes iniciar sesión.');
    }
}
