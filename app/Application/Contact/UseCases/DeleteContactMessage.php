<?php

namespace App\Application\Contact\UseCases;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Models\ContactMessage;

class DeleteContactMessage
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
    ) {}

    public function execute(ContactMessage $contactMessage): void
    {
        $this->contactRepository->delete($contactMessage);
    }
}
