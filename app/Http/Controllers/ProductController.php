<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with([
                'images' => fn($q) => $q->orderBy('sort_order'),
                'category:id,name,slug',
            ])
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->select('id', 'category_id', 'name', 'slug', 'price', 'sale_price')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $reviews = $product->reviews()
            ->approved()
            ->with('user:id,first_name,last_name')
            ->latest()
            ->paginate(5);

        // Single query for avg, total, and distribution (instead of 7 queries)
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
        if (auth()->check()) {
            $userReview = Review::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();
        }

        return view('product-detail', compact('product', 'relatedProducts', 'reviews', 'reviewStats', 'userReview'));
    }
}
