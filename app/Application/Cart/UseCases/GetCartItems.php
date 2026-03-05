<?php

namespace App\Application\Cart\UseCases;

use App\Models\Product;

class GetCartItems
{
    public function execute(): array
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

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
                $total += $lineTotal;

                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->current_price,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                    'image' => $product->primaryImage?->image_url,
                ];
            }
        }

        return [
            'items' => $items,
            'total' => $total,
            'count' => array_sum(array_column($cart, 'quantity')),
        ];
    }
}
