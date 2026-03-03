<?php

namespace App\Domain\User\Services;

use App\Domain\User\Exceptions\UserInactiveException;
use App\Models\User;

class UserDomainService
{
    public function ensureUserIsActive(User $user): void
    {
        if (!$user->is_active) {
            throw new UserInactiveException();
        }
    }

    public function canDeleteUser(User $user, int $currentUserId): bool
    {
        return $user->id !== $currentUserId;
    }
}
