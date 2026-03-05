<?php

namespace App\Application\Cart\UseCases;

use App\Models\Product;

class UpdateCartItem
{
    public function execute(int $productId, int $quantity): int
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            $cart[$productId]['quantity'] = min($quantity, $product->stock);
            session()->put('cart', $cart);
        }

        return array_sum(array_column($cart, 'quantity'));
    }
}
