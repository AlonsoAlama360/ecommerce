<?php

namespace App\Infrastructure\Contact\Repositories;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\DB;

class EloquentContactRepository implements ContactRepositoryInterface
{
    public function findById(int $id): ?ContactMessage
    {
        return ContactMessage::find($id);
    }

    public function create(array $data): ContactMessage
    {
        return ContactMessage::create($data);
    }

    public function update(ContactMessage $contactMessage, array $data): ContactMessage
    {
        $contactMessage->update($data);
        return $contactMessage->fresh();
    }

    public function delete(ContactMessage $contactMessage): void
    {
        $contactMessage->delete();
    }

    public function paginate(array $filters, int $perPage = 15): mixed
    {
        $query = ContactMessage::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(status = 'nuevo') as nuevo,
                SUM(status = 'leido') as leido,
                SUM(status = 'respondido') as respondido
            FROM contact_messages
        ");
    }
}
