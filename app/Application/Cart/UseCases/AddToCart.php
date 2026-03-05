<?php

namespace App\Application\Cart\UseCases;

use App\Models\Product;

class AddToCart
{
    public function execute(int $productId, int $quantity = 1): int
    {
        $product = Product::active()->findOrFail($productId);

        $cart = session()->get('cart', []);
        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $newQty = min($currentQty + $quantity, $product->stock);

        $cart[$product->id] = ['quantity' => $newQty];
        session()->put('cart', $cart);

        return array_sum(array_column($cart, 'quantity'));
    }
}
