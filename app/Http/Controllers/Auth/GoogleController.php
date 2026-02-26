<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Error Google: ' . $e->getMessage(),
            ]);
        }

        // Find by google_id first
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Find by email (existing user linking Google)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                // Create new user
                $nameParts = explode(' ', $googleUser->getName(), 2);

                $user = User::create([
                    'first_name' => $nameParts[0],
                    'last_name' => $nameParts[1] ?? '',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                    'role' => 'cliente',
                    'is_active' => true,
                    'auth_provider' => 'google',
                ]);

                Mail::to($user)->send(new WelcomeMail($user));
            }
        }

        if (!$user->is_active) {
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        Auth::login($user, true);
        session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
