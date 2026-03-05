<?php

namespace App\Application\Cart\UseCases;

class RemoveFromCart
{
    public function execute(int $productId): int
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return array_sum(array_column($cart, 'quantity'));
    }
}
