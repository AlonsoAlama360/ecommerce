<?php

namespace App\Application\Review\UseCases;

use App\Domain\Review\Repositories\ReviewRepositoryInterface;
use App\Models\Review;

class RejectReview
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
    ) {}

    public function execute(Review $review): Review
    {
        return $this->reviewRepository->update($review, ['is_approved' => false]);
    }
}
