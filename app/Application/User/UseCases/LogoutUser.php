<?php

namespace App\Application\User\UseCases;

use App\Application\User\Ports\AuthServiceInterface;

class LogoutUser
{
    public function __construct(
        private AuthServiceInterface $authService,
    ) {}

    public function execute(): void
    {
        $this->authService->logout();
        $this->authService->invalidateSession();
    }
}
