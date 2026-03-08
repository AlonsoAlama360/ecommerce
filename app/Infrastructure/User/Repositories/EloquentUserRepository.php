<?php

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByGoogleId(string $googleId): ?User
    {
        return User::where('google_id', $googleId)->first();
    }

    public function findByFacebookId(string $facebookId): ?User
    {
        return User::where('facebook_id', $facebookId)->first();
    }

    public function create(array $data): User
    {
        $user = new User();
        $user->forceFill($data)->save();
        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->forceFill($data)->save();
        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function paginate(array $filters, int $perPage = 10): mixed
    {
        $query = User::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['status']) && $filters['status'] !== '' && $filters['status'] !== null) {
            $query->where('is_active', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function count(): int
    {
        return User::count();
    }

    public function countActive(): int
    {
        return User::where('is_active', true)->count();
    }

    public function countInactive(): int
    {
        return User::where('is_active', false)->count();
    }

    public function countNewThisWeek(): int
    {
        return User::where('created_at', '>=', now()->subWeek())->count();
    }
}
