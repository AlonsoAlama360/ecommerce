<?php

namespace App\Http\Controllers\Auth;

use App\Application\User\Ports\SocialAuthInterface;
use App\Application\User\UseCases\SocialLogin;
use App\Domain\User\Exceptions\UserInactiveException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class FacebookController extends Controller
{
    public function __construct(
        private SocialAuthInterface $socialAuth,
        private SocialLogin $socialLogin,
    ) {}

    public function redirect()
    {
        return $this->socialAuth->redirect('facebook');
    }

    public function callback()
    {
        try {
            $socialUser = $this->socialAuth->getUser('facebook');
            $this->socialLogin->execute($socialUser);
        } catch (UserInactiveException $e) {
            return redirect()->route('login')->withErrors([
                'email' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            Log::error('Facebook login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Error Facebook: ' . $e->getMessage(),
            ]);
        }

        return redirect()->intended(route('home'));
    }
}
