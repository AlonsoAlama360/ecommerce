<?php

namespace App\Application\Complaint\UseCases;

use App\Domain\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Models\Complaint;

class UpdateComplaint
{
    public function __construct(
        private ComplaintRepositoryInterface $complaintRepository,
    ) {}

    public function execute(Complaint $complaint, array $data): Complaint
    {
        if ($data['status'] === 'resuelto' && !$complaint->response_date) {
            $data['response_date'] = now();
        }

        return $this->complaintRepository->update($complaint, $data);
    }
}
