<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ShowProduct
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(string $slug): array
    {
        $product = $this->productRepository->findBySlugWithRelations($slug);

        if (!$product) {
            abort(404);
        }

        $relatedProducts = $this->productRepository->getRelatedProducts($product);

        $reviews = $product->reviews()
            ->approved()
            ->with('user:id,first_name,last_name')
            ->latest()
            ->paginate(5);

        $ratingData = $product->reviews()->approved()
            ->selectRaw('COUNT(*) as total, AVG(rating) as avg_rating, rating')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $reviewStats = [
            'average' => round((float) $product->reviews()->approved()->avg('rating'), 1) ?: 0,
            'total' => $ratingData->sum(),
            'distribution' => [],
        ];
        for ($i = 5; $i >= 1; $i--) {
            $reviewStats['distribution'][$i] = (int) ($ratingData[$i] ?? 0);
        }

        $userReview = null;
        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();
        }

        return compact('product', 'relatedProducts', 'reviews', 'reviewStats', 'userReview');
    }
}
