<?php

namespace App\Infrastructure\Subscriber\Repositories;

use App\Domain\Subscriber\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;

class EloquentSubscriberRepository implements SubscriberRepositoryInterface
{
    public function findByEmail(string $email): ?Subscriber
    {
        return Subscriber::where('email', $email)->first();
    }

    public function create(array $data): Subscriber
    {
        return Subscriber::create($data);
    }

    public function update(Subscriber $subscriber, array $data): Subscriber
    {
        $subscriber->update($data);
        return $subscriber->fresh();
    }

    public function delete(Subscriber $subscriber): void
    {
        $subscriber->delete();
    }

    public function paginate(array $filters, int $perPage = 15): mixed
    {
        $query = Subscriber::query();

        if (!empty($filters['search'])) {
            $query->where('email', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('is_active', $filters['status']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(is_active = 1) as active,
                SUM(is_active = 0) as inactive,
                SUM(created_at >= ?) as new_week
            FROM subscribers
        ", [now()->subWeek()->toDateTimeString()]);
    }
}
