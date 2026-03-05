<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Jobs\SendReviewRequestEmail;
use App\Mail\ShippingConfirmationMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class UpdateOrderStatus
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(Order $order, string $status, ?string $trackingNumber = null): Order
    {
        $data = ['status' => $status];

        if ($status === 'enviado' && $trackingNumber) {
            $data['tracking_number'] = $trackingNumber;
        }

        $order = $this->orderRepository->update($order, $data);

        if ($status === 'enviado') {
            $order->load('items');
            Mail::to($order->customer_email)->send(new ShippingConfirmationMail($order));
        }

        if ($status === 'entregado') {
            SendReviewRequestEmail::dispatch($order)->delay(now()->addDays(3));
        }

        return $order;
    }
}
