<?php

namespace App\Application\User\Ports;

use App\Application\User\DTOs\SocialUserDTO;

interface SocialAuthInterface
{
    public function redirect(string $provider): mixed;

    public function getUser(string $provider): SocialUserDTO;
}
