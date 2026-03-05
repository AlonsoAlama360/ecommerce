<?php

namespace App\Domain\Contact\Repositories;

use App\Models\ContactMessage;

interface ContactRepositoryInterface
{
    public function findById(int $id): ?ContactMessage;

    public function create(array $data): ContactMessage;

    public function update(ContactMessage $contactMessage, array $data): ContactMessage;

    public function delete(ContactMessage $contactMessage): void;

    public function paginate(array $filters, int $perPage = 15): mixed;

    public function getStats(): object;
}
