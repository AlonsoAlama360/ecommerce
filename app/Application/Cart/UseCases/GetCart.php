<?php

namespace App\Application\Cart\UseCases;

use App\Models\Product;

class GetCart
{
    public function execute(): array
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;
        $totalDiscount = 0;

        if (!empty($cart)) {
            $products = Product::whereIn('id', array_keys($cart))
                ->with('primaryImage:id,product_id,image_url,alt_text')
                ->get()
                ->keyBy('id');

            foreach ($cart as $productId => $item) {
                $product = $products->get($productId);
                if (!$product) continue;

                $qty = $item['quantity'];
                $lineTotal = $product->current_price * $qty;
                $lineDiscount = $product->sale_price
                    ? ($product->price - $product->sale_price) * $qty
                    : 0;

                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                ];

                $subtotal += $product->price * $qty;
                $totalDiscount += $lineDiscount;
            }
        }

        $total = $subtotal - $totalDiscount;
        $totalItems = array_sum(array_column($cart, 'quantity'));

        $suggestedProducts = Product::active()
            ->whereNotIn('id', array_keys($cart))
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->select('id', 'name', 'slug', 'price', 'sale_price')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'totalDiscount' => $totalDiscount,
            'total' => $total,
            'totalItems' => $totalItems,
            'suggestedProducts' => $suggestedProducts,
        ];
    }
}
