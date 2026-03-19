<?php

namespace App\Services;

use App\Mail\Admin\LowStockImmediateAlertMail;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\StockMovement;
use App\Notifications\Admin\LowStockNotification;
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
            $signedQty = $quantity;
            // Atomic increment
            Product::where('id', $product->id)->increment('stock', $quantity);
            $product->refresh();
            $stockAfter = $product->stock;
        } else {
            $signedQty = -$quantity;
            // Atomic decrement with floor at 0 to prevent overselling
            $affected = Product::where('id', $product->id)
                ->where('stock', '>=', $quantity)
                ->decrement('stock', $quantity);

            if ($affected === 0) {
                // Not enough stock — decrement to 0 as fallback
                Product::where('id', $product->id)
                    ->where('stock', '>', 0)
                    ->update(['stock' => 0]);
                $product->refresh();
                $stockAfter = $product->stock;
            } else {
                $product->refresh();
                $stockAfter = $product->stock;
            }
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

        if ($type === 'salida') {
            static::checkLowStock($product, $stockBefore, $stockAfter);
        }

        return $movement;
    }

    private static function checkLowStock(Product $product, int $stockBefore, int $stockAfter): void
    {
        $threshold = (int) SiteSetting::get('low_stock_threshold', 5);

        $crossedThreshold = $stockBefore > $threshold && $stockAfter <= $threshold;
        $reachedZero = $stockBefore > 0 && $stockAfter === 0;

        if ($crossedThreshold || $reachedZero) {
            $product->load('category');
            AdminNotificationService::send(
                'notify_low_stock',
                new LowStockImmediateAlertMail($product, $threshold)
            );
            AdminNotificationService::notify('low_stock', new LowStockNotification($product));
        }
    }
}
