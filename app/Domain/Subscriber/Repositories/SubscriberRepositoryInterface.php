<?php

namespace App\Domain\Subscriber\Repositories;

use App\Models\Subscriber;

interface SubscriberRepositoryInterface
{
    public function findByEmail(string $email): ?Subscriber;

    public function create(array $data): Subscriber;

    public function update(Subscriber $subscriber, array $data): Subscriber;

    public function delete(Subscriber $subscriber): void;

    public function paginate(array $filters, int $perPage = 15): mixed;

    public function getStats(): object;
}
