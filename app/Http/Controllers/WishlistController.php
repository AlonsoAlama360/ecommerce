<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Auth::user()
            ->wishlistProducts()
            ->active()
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->with('category:id,name,slug')
            ->select('products.id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'is_featured')
            ->orderByDesc('wishlists.created_at')
            ->paginate(12);

        return view('wishlist', compact('products'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $exists = $user->wishlistProducts()->where('product_id', $productId)->exists();

        if ($exists) {
            $user->wishlistProducts()->detach($productId);
            $status = 'removed';
        } else {
            $user->wishlistProducts()->attach($productId);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
            'count' => $user->wishlistProducts()->count(),
        ]);
    }

    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0, 'ids' => []]);
        }

        $user = Auth::user();

        return response()->json([
            'count' => $user->wishlistProducts()->count(),
            'ids' => $user->wishlistProducts()->pluck('product_id')->toArray(),
        ]);
    }
}
