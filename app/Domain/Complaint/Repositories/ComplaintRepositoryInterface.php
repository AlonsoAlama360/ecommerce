<?php

namespace App\Domain\Complaint\Repositories;

use App\Models\Complaint;

interface ComplaintRepositoryInterface
{
    public function findById(int $id): ?Complaint;

    public function create(array $data): Complaint;

    public function update(Complaint $complaint, array $data): Complaint;

    public function paginate(array $filters, int $perPage = 15): mixed;

    public function getStats(): object;
}
