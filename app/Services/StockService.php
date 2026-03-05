<?php

namespace App\Services;

use App\Mail\Admin\LowStockImmediateAlertMail;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;

class StockService
{
    public static function increment(Product $product, int $quantity, ?Model $reference = null, ?string $notes = null): StockMovement
    {
        return static::record($product, 'entrada', $quantity, $reference, $notes);
    }

    public static function decrement(Product $product, int $quantity, ?Model $reference = null, ?string $notes = null): StockMovement
    {
        return static::record($product, 'salida', $quantity, $reference, $notes);
    }

    public static function adjust(Product $product, int $newStock, ?string $notes = null): StockMovement
    {
        $currentStock = $product->stock;
        $diff = $newStock - $currentStock;

        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => 'ajuste',
            'quantity' => $diff,
            'stock_before' => $currentStock,
            'stock_after' => $newStock,
            'notes' => $notes,
            'created_by' => auth()->id(),
            'created_at' => now(),
        ]);

        $product->update(['stock' => $newStock]);

        static::checkLowStock($product, $currentStock, $newStock);

        return $movement;
    }

    private static function record(Product $product, string $type, int $quantity, ?Model $reference, ?string $notes): StockMovement
    {
        $stockBefore = $product->stock;

        if ($type === 'entrada') {
            $stockAfter = $stockBefore + $quantity;
            $signedQty = $quantity;
        } else {
            $stockAfter = max(0, $stockBefore - $quantity);
            $signedQty = -$quantity;
        }

        $data = [
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $signedQty,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'notes' => $notes,
            'created_by' => auth()->id(),
            'created_at' => now(),
        ];

        if ($reference) {
            $data['reference_type'] = get_class($reference);
            $data['reference_id'] = $reference->getKey();
        }

        $movement = StockMovement::create($data);

        $product->update(['stock' => $stockAfter]);

        if ($type === 'salida') {
            static::checkLowStock($product, $stockBefore, $stockAfter);
        }

        return $movement;
    }

    private static function checkLowStock(Product $product, int $stockBefore, int $stockAfter): void
    {
        $threshold = (int) SiteSetting::get('low_stock_threshold', 5);

        // Only alert when crossing the threshold (was above, now at or below)
        if ($stockBefore > $threshold && $stockAfter <= $threshold) {
            $product->load('category');
            AdminNotificationService::send(
                'notify_low_stock',
                new LowStockImmediateAlertMail($product, $threshold)
            );
        }
    }
}
