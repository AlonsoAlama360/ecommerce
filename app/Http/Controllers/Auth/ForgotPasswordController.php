<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
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

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('status', 'Si el email está registrado, recibirás un enlace de recuperación.');
        }

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate token
        $token = Str::random(64);

        // Store token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => bcrypt($token),
            'created_at' => now(),
        ]);

        // Send email
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
        Mail::to($request->email)->send(new ResetPasswordMail($user, $resetUrl));

        return back()->with('status', 'Si el email está registrado, recibirás un enlace de recuperación.');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresa un email válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Este enlace de recuperación no es válido.']);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Este enlace ha expirado. Solicita uno nuevo.']);
        }

        // Verify token
        if (!password_verify($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Este enlace de recuperación no es válido.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No se encontró un usuario con este email.']);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente. Ya puedes iniciar sesión.');
    }
}
