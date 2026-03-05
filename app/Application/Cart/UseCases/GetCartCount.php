<?php

namespace App\Application\Cart\UseCases;

class GetCartCount
{
    public function execute(): int
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
}
