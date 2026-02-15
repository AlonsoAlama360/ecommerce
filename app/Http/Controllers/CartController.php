<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
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

        return view('cart', compact(
            'cartItems', 'subtotal', 'totalDiscount', 'total', 'totalItems', 'suggestedProducts'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $product = Product::active()->findOrFail($request->product_id);
        $quantity = $request->integer('quantity', 1);

        $cart = session()->get('cart', []);
        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $newQty = min($currentQty + $quantity, $product->stock);

        $cart[$product->id] = ['quantity' => $newQty];
        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Producto agregado al carrito',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
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

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            $cart[$productId]['quantity'] = min($request->quantity, $product->stock);
            session()->put('cart', $cart);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Cantidad actualizada',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back();
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Producto eliminado',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json([
            'count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    public function items()
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

        return response()->json([
            'items' => $items,
            'total' => $total,
            'count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }
}
