<?php

namespace App\Application\Complaint\UseCases;

use App\Application\Complaint\DTOs\ComplaintFiltersDTO;
use App\Domain\Complaint\Repositories\ComplaintRepositoryInterface;

class ListComplaints
{
    public function __construct(
        private ComplaintRepositoryInterface $complaintRepository,
    ) {}

    public function execute(ComplaintFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
            'type' => $dto->type,
        ];

        $complaints = $this->complaintRepository->paginate($filters, $dto->perPage);
        $stats = $this->complaintRepository->getStats();

        return [
            'complaints' => $complaints,
            'stats' => [
                'total' => (int) $stats->total,
                'pendiente' => (int) ($stats->pendiente ?? 0),
                'en_proceso' => (int) ($stats->en_proceso ?? 0),
                'resuelto' => (int) ($stats->resuelto ?? 0),
            ],
        ];
    }
}
