<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing movements
        StockMovement::truncate();

        // Build a running stock tracker per product (start from current stock and work backwards to set initial)
        // Instead, we'll process chronologically and simulate stock changes

        // Collect all events chronologically
        $events = collect();

        // Purchases that were received → entrada
        $purchases = Purchase::with('items')->where('status', 'recibido')->get();
        foreach ($purchases as $purchase) {
            $date = $purchase->received_date ?? $purchase->created_at;
            foreach ($purchase->items as $item) {
                if (!$item->product_id) continue;
                $events->push([
                    'product_id' => $item->product_id,
                    'type' => 'entrada',
                    'quantity' => $item->quantity,
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'notes' => "Compra {$purchase->purchase_number} recibida",
                    'created_by' => $purchase->created_by,
                    'created_at' => $date,
                ]);
            }
        }

        // Orders → salida
        $orders = Order::with('items')->get();
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if (!$item->product_id) continue;
                $events->push([
                    'product_id' => $item->product_id,
                    'type' => 'salida',
                    'quantity' => $item->quantity,
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                    'notes' => "Venta {$order->order_number}",
                    'created_by' => $order->created_by,
                    'created_at' => $order->created_at,
                ]);
            }
        }

        // Sort by date ascending
        $events = $events->sortBy('created_at');

        // We need to figure out initial stock for each product
        // Current stock = initial + sum(entradas) - sum(salidas)
        // So initial = current - sum(entradas) + sum(salidas)
        $productStocks = [];
        $products = Product::all()->keyBy('id');

        foreach ($products as $product) {
            $totalIn = $events->where('product_id', $product->id)->where('type', 'entrada')->sum('quantity');
            $totalOut = $events->where('product_id', $product->id)->where('type', 'salida')->sum('quantity');
            $initial = max(0, $product->stock - $totalIn + $totalOut);
            $productStocks[$product->id] = $initial;
        }

        // Now create movements chronologically
        foreach ($events as $event) {
            $pid = $event['product_id'];
            if (!isset($productStocks[$pid])) continue;

            $stockBefore = $productStocks[$pid];

            if ($event['type'] === 'entrada') {
                $stockAfter = $stockBefore + $event['quantity'];
                $signedQty = $event['quantity'];
            } else {
                $stockAfter = max(0, $stockBefore - $event['quantity']);
                $signedQty = -$event['quantity'];
            }

            StockMovement::create([
                'product_id' => $pid,
                'type' => $event['type'],
                'quantity' => $signedQty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => $event['reference_type'],
                'reference_id' => $event['reference_id'],
                'notes' => $event['notes'],
                'created_by' => $event['created_by'],
                'created_at' => $event['created_at'],
            ]);

            $productStocks[$pid] = $stockAfter;
        }
    }
}
