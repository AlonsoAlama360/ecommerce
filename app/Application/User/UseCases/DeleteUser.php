<?php

namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Services\UserDomainService;
use App\Models\User;

class DeleteUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserDomainService $domainService,
    ) {}

    public function execute(User $user, int $currentUserId): void
    {
        if (!$this->domainService->canDeleteUser($user, $currentUserId)) {
            throw new \LogicException('No puedes eliminar tu propia cuenta.');
        }

        $this->userRepository->delete($user);
    }
}
