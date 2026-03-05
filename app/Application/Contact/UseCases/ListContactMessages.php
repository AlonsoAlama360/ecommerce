<?php

namespace App\Application\Contact\UseCases;

use App\Application\Contact\DTOs\ContactFiltersDTO;
use App\Domain\Contact\Repositories\ContactRepositoryInterface;

class ListContactMessages
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
    ) {}

    public function execute(ContactFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
        ];

        $messages = $this->contactRepository->paginate($filters, $dto->perPage);
        $stats = $this->contactRepository->getStats();

        return [
            'messages' => $messages,
            'stats' => [
                'total' => (int) $stats->total,
                'nuevo' => (int) ($stats->nuevo ?? 0),
                'leido' => (int) ($stats->leido ?? 0),
                'respondido' => (int) ($stats->respondido ?? 0),
            ],
        ];
    }
}
