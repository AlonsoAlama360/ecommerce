<?php

namespace App\Application\Review\UseCases;

use App\Domain\Review\Repositories\ReviewRepositoryInterface;
use App\Models\Review;

class DeleteReview
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
    ) {}

    public function execute(Review $review): void
    {
        $this->reviewRepository->delete($review);
    }
}
