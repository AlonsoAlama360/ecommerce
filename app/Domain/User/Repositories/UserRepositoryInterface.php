<?php

namespace App\Domain\User\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function findByGoogleId(string $googleId): ?User;

    public function findByFacebookId(string $facebookId): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): void;

    public function paginate(array $filters, int $perPage = 10): mixed;

    public function count(): int;

    public function countActive(): int;

    public function countInactive(): int;

    public function countNewThisWeek(): int;
}
