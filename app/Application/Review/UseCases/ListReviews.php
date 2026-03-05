<?php

namespace App\Application\Review\UseCases;

use App\Application\Review\DTOs\ReviewFiltersDTO;
use App\Domain\Review\Repositories\ReviewRepositoryInterface;

class ListReviews
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
    ) {}

    public function execute(ReviewFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
            'rating' => $dto->rating,
            'featured' => $dto->featured,
        ];

        $reviews = $this->reviewRepository->paginate($filters);
        $stats = $this->reviewRepository->getStats();

        return [
            'reviews' => $reviews,
            'totalReviews' => (int) $stats->total,
            'approvedReviews' => (int) ($stats->approved ?? 0),
            'pendingReviews' => (int) ($stats->pending ?? 0),
            'averageRating' => round((float) ($stats->avg_rating ?? 0), 1),
            'featuredCount' => (int) ($stats->featured ?? 0),
        ];
    }
}
