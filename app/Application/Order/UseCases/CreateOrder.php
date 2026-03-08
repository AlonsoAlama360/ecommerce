<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTOs\CreateOrderDTO;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Mail\Admin\NewOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\WelcomeMail;
use App\Models\AbandonedCart;
use App\Services\AdminNotificationService;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateOrder
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(CreateOrderDTO $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            $user = null;
            if (!empty($dto->customerEmail)) {
                $user = User::where('email', $dto->customerEmail)->first();
            }

            if (!$user && !empty($dto->customerEmail)) {
                $nameParts = explode(' ', $dto->customerName, 2);
                $user = new User();
                $user->forceFill([
                    'first_name' => $nameParts[0],
                    'last_name' => $nameParts[1] ?? '',
                    'email' => $dto->customerEmail,
                    'phone' => $dto->customerPhone,
                    'password' => bcrypt(Str::random(12)),
                    'role' => 'cliente',
                    'is_active' => true,
                    'auth_provider' => 'form',
                ])->save();

                Mail::to($user)->queue(new WelcomeMail($user));
            }

            $subtotal = 0;
            $itemsData = [];

            foreach ($dto->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = $product->sale_price ?? $product->price;
                $lineTotal = $price * $item['quantity'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ];
            }

            if ($user && !$user->address && !empty($dto->shippingAddress)) {
                $user->update(['address' => $dto->shippingAddress]);
            }

            $order = $this->orderRepository->create([
                'user_id' => $user?->id,
                'source' => $dto->source,
                'status' => 'confirmado',
                'payment_method' => $dto->paymentMethod,
                'payment_status' => $dto->paymentStatus,
                'payment_reference' => $dto->paymentReference,
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'customer_name' => $dto->customerName,
                'customer_phone' => $dto->customerPhone,
                'customer_email' => $dto->customerEmail,
                'shipping_address' => $dto->shippingAddress,
                'customer_notes' => $dto->customerNotes,
                'admin_notes' => $dto->adminNotes,
                'created_by' => $dto->createdBy,
            ]);

            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    StockService::decrement($product, $itemData['quantity'], $order, "Venta {$order->order_number}");
                }
            }

            if ($user) {
                AbandonedCart::where('user_id', $user->id)
                    ->whereNull('recovered_at')
                    ->update(['recovered_at' => now()]);
            }

            if ($order->customer_email) {
                try {
                    Mail::to($order->customer_email)->queue(new OrderConfirmationMail($order->load('items')));
                } catch (\Exception $e) {
                    \Log::warning("No se pudo enviar email de confirmación para {$order->order_number}: " . $e->getMessage());
                }
            }

            AdminNotificationService::send('notify_new_order', new NewOrderNotificationMail($order->load('items')));

            return $order;
        });
    }
}
