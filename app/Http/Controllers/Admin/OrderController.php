<?php

namespace App\Http\Controllers\Admin;

use App\Application\Order\DTOs\CreateOrderDTO;
use App\Application\Order\DTOs\OrderFiltersDTO;
use App\Application\Order\DTOs\UpdateOrderDTO;
use App\Application\Order\UseCases\CreateOrder;
use App\Application\Order\UseCases\DeleteOrder;
use App\Application\Order\UseCases\ExportOrders;
use App\Application\Order\UseCases\ListOrders;
use App\Application\Order\UseCases\ShowOrder;
use App\Application\Order\UseCases\UpdateOrder;
use App\Application\Order\UseCases\UpdateOrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request, ListOrders $listOrders)
    {
        $dto = OrderFiltersDTO::fromRequest($request);
        $data = $listOrders->execute($dto);

        return view('admin.orders.index', $data);
    }

    public function create()
    {
        return redirect()->route('admin.orders.index');
    }

    public function store(Request $request, CreateOrder $createOrder)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:efectivo,transferencia,yape_plin,tarjeta,culqi',
            'payment_status' => 'required|in:pendiente,pagado,fallido',
            'admin_notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $dto = CreateOrderDTO::fromRequest($request);
        $order = $createOrder->execute($dto);

        return redirect()->route('admin.orders.index')
            ->with('success', "Venta {$order->order_number} creada exitosamente.");
    }

    public function show(Order $order, ShowOrder $showOrder)
    {
        $order = $showOrder->execute($order);
        return response()->json($order);
    }

    public function update(Request $request, Order $order, UpdateOrder $updateOrder)
    {
        $request->validate([
            'status' => 'sometimes|in:pendiente,confirmado,en_preparacion,enviado,entregado,cancelado',
            'payment_status' => 'sometimes|in:pendiente,pagado,fallido',
            'payment_method' => 'sometimes|in:efectivo,transferencia,yape_plin,tarjeta,culqi',
            'admin_notes' => 'nullable|string|max:2000',
            'shipping_address' => 'nullable|string|max:1000',
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'sometimes|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        $dto = UpdateOrderDTO::fromRequest($request);
        $updatedOrder = $updateOrder->execute($dto, $order);

        if ($request->ajax()) {
            return response()->json(['message' => 'Venta actualizada', 'order' => $updatedOrder]);
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Venta actualizada exitosamente.');
    }

    public function updateStatus(Request $request, Order $order, UpdateOrderStatus $updateStatus)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,confirmado,en_preparacion,enviado,entregado,cancelado',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order = $updateStatus->execute($order, $validated['status'], $validated['tracking_number'] ?? null);

        return response()->json([
            'message' => 'Estado actualizado',
            'status' => $order->status,
            'status_label' => $order->status_label,
        ]);
    }

    public function destroy(Order $order, DeleteOrder $deleteOrder)
    {
        $deleteOrder->execute($order);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Venta eliminada exitosamente.');
    }

    public function export(Request $request, ExportOrders $exportOrders)
    {
        $dto = OrderFiltersDTO::fromRequest($request);
        return $exportOrders->execute($dto);
    }

    public function searchProducts(Request $request, OrderRepositoryInterface $orderRepository)
    {
        $search = $request->get('q', '');
        return response()->json($orderRepository->searchProducts($search));
    }

    public function searchUsers(Request $request, OrderRepositoryInterface $orderRepository)
    {
        $search = $request->get('q', '');
        return response()->json($orderRepository->searchUsers($search));
    }
}
