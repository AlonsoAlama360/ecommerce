<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
        } catch (\Exception $e) {
            \Log::error('Facebook login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Error Facebook: ' . $e->getMessage(),
            ]);
        }

        // Find by facebook_id first
        $user = User::where('facebook_id', $facebookUser->getId())->first();

        if (!$user) {
            // Find by email (existing user linking Facebook)
            $user = User::where('email', $facebookUser->getEmail())->first();

            if ($user) {
                $user->update(['facebook_id' => $facebookUser->getId()]);
            } else {
                // Create new user
                $nameParts = explode(' ', $facebookUser->getName(), 2);

                $user = User::create([
                    'first_name' => $nameParts[0],
                    'last_name' => $nameParts[1] ?? '',
                    'email' => $facebookUser->getEmail(),
                    'facebook_id' => $facebookUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                    'role' => 'cliente',
                    'is_active' => true,
                    'auth_provider' => 'facebook',
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
