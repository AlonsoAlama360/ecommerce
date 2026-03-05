<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTOs\OrderFiltersDTO;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportOrders
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(OrderFiltersDTO $dto): StreamedResponse
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
            'source' => $dto->source,
            'payment_method' => $dto->paymentMethod,
            'payment_status' => $dto->paymentStatus,
            'date_from' => $dto->dateFrom,
            'date_to' => $dto->dateTo,
        ];

        $query = $this->orderRepository->exportQuery($filters);
        $filename = 'ventas_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'N° Orden', 'Fecha', 'Cliente', 'Email', 'Teléfono',
                'Dirección de Envío', 'Origen', 'Estado', 'Método de Pago',
                'Estado de Pago', 'Productos', 'Cant. Items', 'Subtotal',
                'Descuento', 'Envío', 'Total', 'Notas',
            ], ';');

            $query->with(['items'])->chunk(500, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    $products = $order->items->map(function ($item) {
                        return $item->product_name . ' (x' . $item->quantity . ' - S/' . number_format($item->unit_price, 2) . ')';
                    })->implode(' | ');

                    fputcsv($handle, [
                        $order->order_number,
                        $order->created_at->format('d/m/Y H:i'),
                        $order->customer_name,
                        $order->customer_email ?? '',
                        $order->customer_phone ?? '',
                        $order->shipping_address ?? '',
                        $order->source === 'web' ? 'Web' : 'Admin',
                        Order::STATUS_LABELS[$order->status] ?? $order->status,
                        Order::PAYMENT_METHODS[$order->payment_method] ?? $order->payment_method,
                        Order::PAYMENT_STATUS_LABELS[$order->payment_status] ?? $order->payment_status,
                        $products,
                        $order->items->sum('quantity'),
                        number_format($order->subtotal, 2),
                        number_format($order->discount_amount, 2),
                        number_format($order->shipping_cost, 2),
                        number_format($order->total, 2),
                        $order->admin_notes ?? '',
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
