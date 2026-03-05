<?php

namespace App\Http\Controllers;

use App\Application\Wishlist\UseCases\GetWishlistCount;
use App\Application\Wishlist\UseCases\ListUserWishlist;
use App\Application\Wishlist\UseCases\ToggleWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct(
        private readonly ListUserWishlist $listUserWishlist,
        private readonly ToggleWishlist $toggleWishlist,
        private readonly GetWishlistCount $getWishlistCount,
    ) {}

    public function index()
    {
        $products = $this->listUserWishlist->execute(Auth::user());

        return view('wishlist', compact('products'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $result = $this->toggleWishlist->execute(
            user: Auth::user(),
            productId: $request->product_id
        );

        return response()->json($result);
    }

    public function count()
    {
        $result = $this->getWishlistCount->execute(Auth::user());

        return response()->json($result);
    }
}
