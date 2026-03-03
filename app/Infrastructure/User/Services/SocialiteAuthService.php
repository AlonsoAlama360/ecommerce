<?php

namespace App\Infrastructure\User\Services;

use App\Application\User\DTOs\SocialUserDTO;
use App\Application\User\Ports\SocialAuthInterface;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthService implements SocialAuthInterface
{
    public function redirect(string $provider): mixed
    {
        return Socialite::driver($provider)->redirect();
    }

    public function getUser(string $provider): SocialUserDTO
    {
        $socialUser = Socialite::driver($provider)
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->user();

        return new SocialUserDTO(
            provider: $provider,
            providerId: $socialUser->getId(),
            name: $socialUser->getName(),
            email: $socialUser->getEmail(),
        );
    }
}
