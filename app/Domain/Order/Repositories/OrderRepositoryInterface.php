<?php

namespace App\Domain\Order\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;

    public function create(array $data): Order;

    public function update(Order $order, array $data): Order;

    public function delete(Order $order): void;

    public function paginate(array $filters, int $perPage = 10): mixed;

    public function getStats(): object;

    public function getCustomerOrders(int $userId, ?string $status = null, int $perPage = 10): mixed;

    public function getCustomerStatusCounts(int $userId): Collection;

    public function searchProducts(string $search, int $limit = 10): Collection;

    public function searchUsers(string $search, int $limit = 10): Collection;

    public function exportQuery(array $filters): mixed;
}
