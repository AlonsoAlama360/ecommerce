<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UserFiltersDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;

class ListUsers
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(UserFiltersDTO $filters): array
    {
        return [
            'users' => $this->userRepository->paginate([
                'search' => $filters->search,
                'role' => $filters->role,
                'status' => $filters->status,
                'date_from' => $filters->dateFrom,
                'date_to' => $filters->dateTo,
            ], $filters->perPage),
            'totalUsers' => $this->userRepository->count(),
            'activeUsers' => $this->userRepository->countActive(),
            'inactiveUsers' => $this->userRepository->countInactive(),
            'newUsersWeek' => $this->userRepository->countNewThisWeek(),
        ];
    }
}
