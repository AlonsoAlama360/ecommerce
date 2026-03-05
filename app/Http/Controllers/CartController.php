<?php

namespace App\Http\Controllers;

use App\Application\Cart\UseCases\AddToCart;
use App\Application\Cart\UseCases\GetCart;
use App\Application\Cart\UseCases\GetCartCount;
use App\Application\Cart\UseCases\GetCartItems;
use App\Application\Cart\UseCases\RemoveFromCart;
use App\Application\Cart\UseCases\UpdateCartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private GetCart $getCart,
        private AddToCart $addToCart,
        private UpdateCartItem $updateCartItem,
        private RemoveFromCart $removeFromCart,
        private GetCartCount $getCartCount,
        private GetCartItems $getCartItems,
    ) {}

    public function index()
    {
        $result = $this->getCart->execute();

        return view('cart', [
            'cartItems' => $result['cartItems'],
            'subtotal' => $result['subtotal'],
            'totalDiscount' => $result['totalDiscount'],
            'total' => $result['total'],
            'totalItems' => $result['totalItems'],
            'suggestedProducts' => $result['suggestedProducts'],
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $cartCount = $this->addToCart->execute(
            $request->input('product_id'),
            $request->integer('quantity', 1)
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Producto agregado al carrito',
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', '¡Producto agregado al carrito!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cartCount = $this->updateCartItem->execute(
            $request->input('product_id'),
            $request->integer('quantity')
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Cantidad actualizada',
                'cart_count' => $cartCount,
            ]);
        }

        return back();
    }

    public function remove(Request $request)
    {
        $cartCount = $this->removeFromCart->execute($request->input('product_id'));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Producto eliminado',
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount->execute(),
        ]);
    }

    public function items()
    {
        return response()->json($this->getCartItems->execute());
    }
}
