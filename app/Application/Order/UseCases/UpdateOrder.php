<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTOs\UpdateOrderDTO;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class UpdateOrder
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(UpdateOrderDTO $dto, Order $order): Order
    {
        return DB::transaction(function () use ($dto, $order) {
            if ($dto->items !== null) {
                // Restore stock from old items
                foreach ($order->items as $oldItem) {
                    if ($oldItem->product_id) {
                        $oldProduct = Product::find($oldItem->product_id);
                        if ($oldProduct) {
                            StockService::increment($oldProduct, $oldItem->quantity, $order, "Reversión edición venta {$order->order_number}");
                        }
                    }
                }

                $order->items()->delete();

                $subtotal = 0;
                foreach ($dto->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $price = $product->sale_price ?? $product->price;
                    $lineTotal = $price * $item['quantity'];
                    $subtotal += $lineTotal;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $item['quantity'],
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]);

                    StockService::decrement($product, $item['quantity'], $order, "Edición venta {$order->order_number}");
                }

                $updateData['subtotal'] = $subtotal;
                $updateData['total'] = $subtotal - $order->discount_amount + $order->shipping_cost;
            }

            $updateData['status'] = $dto->status ?? $order->status;
            $updateData['payment_status'] = $dto->paymentStatus ?? $order->payment_status;
            $updateData['payment_method'] = $dto->paymentMethod ?? $order->payment_method;
            $updateData['admin_notes'] = $dto->adminNotes;
            $updateData['shipping_address'] = $dto->shippingAddress ?? $order->shipping_address;
            $updateData['customer_name'] = $dto->customerName ?? $order->customer_name;
            $updateData['customer_email'] = $dto->customerEmail ?? $order->customer_email;
            $updateData['customer_phone'] = $dto->customerPhone ?? $order->customer_phone;

            return $this->orderRepository->update($order, $updateData);
        });
    }
}
