<?php

namespace App\Application\Contact\UseCases;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Models\ContactMessage;

class UpdateContactMessage
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
    ) {}

    public function execute(ContactMessage $contactMessage, array $data): ContactMessage
    {
        return $this->contactRepository->update($contactMessage, $data);
    }
}
