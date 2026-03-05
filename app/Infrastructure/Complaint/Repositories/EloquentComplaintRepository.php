<?php

namespace App\Infrastructure\Complaint\Repositories;

use App\Domain\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Models\Complaint;
use Illuminate\Support\Facades\DB;

class EloquentComplaintRepository implements ComplaintRepositoryInterface
{
    public function findById(int $id): ?Complaint
    {
        return Complaint::find($id);
    }

    public function create(array $data): Complaint
    {
        return Complaint::create($data);
    }

    public function update(Complaint $complaint, array $data): Complaint
    {
        $complaint->update($data);
        return $complaint->fresh();
    }

    public function paginate(array $filters, int $perPage = 15): mixed
    {
        $query = Complaint::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('complaint_number', 'like', "%{$search}%")
                  ->orWhere('consumer_name', 'like', "%{$search}%")
                  ->orWhere('consumer_email', 'like', "%{$search}%")
                  ->orWhere('consumer_document_number', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('complaint_type', $filters['type']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(status = 'pendiente') as pendiente,
                SUM(status = 'en_proceso') as en_proceso,
                SUM(status = 'resuelto') as resuelto
            FROM complaints
        ");
    }
}
