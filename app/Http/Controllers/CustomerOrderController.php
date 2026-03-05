<?php

namespace App\Http\Controllers;

use App\Application\Order\UseCases\ListCustomerOrders;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index(Request $request, ListCustomerOrders $listOrders)
    {
        $status = $request->filled('status') ? $request->status : null;
        $data = $listOrders->execute(Auth::id(), $status);

        return view('orders.index', $data);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product.primaryImage']);

        return view('orders.show', compact('order'));
    }
}
