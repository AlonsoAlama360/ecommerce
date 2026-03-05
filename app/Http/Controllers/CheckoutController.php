<?php

namespace App\Http\Controllers;

use App\Application\Cart\UseCases\GetCart;
use App\Application\Order\DTOs\CreateOrderDTO;
use App\Application\Order\UseCases\CreateOrder;
use Culqi\Culqi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(GetCart $getCart)
    {
        $cart = $getCart->execute();

        if (empty($cart['cartItems'])) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío.');
        }

        $user = auth()->user();

        return view('checkout', [
            'cartItems' => $cart['cartItems'],
            'subtotal' => $cart['subtotal'],
            'totalDiscount' => $cart['totalDiscount'],
            'total' => $cart['total'],
            'totalItems' => $cart['totalItems'],
            'user' => $user,
            'culqiPublicKey' => config('culqi.public_key'),
        ]);
    }

    public function createCulqiOrder(Request $request)
    {
        $cart = app(GetCart::class)->execute();

        if (empty($cart['cartItems'])) {
            return response()->json(['error' => 'Carrito vacío'], 422);
        }

        $totalInCents = (int) round($cart['total'] * 100);

        if ($totalInCents < 600) {
            return response()->json(['error' => 'El monto mínimo para pagar con tarjeta o Yape es S/ 6.00.'], 422);
        }

        try {
            $culqi = new Culqi(['api_key' => config('culqi.secret_key')]);

            $order = $culqi->Orders->create([
                'amount' => $totalInCents,
                'currency_code' => 'PEN',
                'description' => 'Pedido en ' . config('app.name'),
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
                'client_details' => [
                    'first_name' => $request->input('customer_name', auth()->user()->first_name),
                    'last_name' => auth()->user()->last_name ?? '',
                    'email' => $request->input('customer_email', auth()->user()->email),
                    'phone_number' => $request->input('customer_phone', auth()->user()->phone ?? ''),
                ],
                'expiration_date' => now()->addHours(1)->timestamp,
            ]);

            // Culqi puede devolver string JSON en caso de error sin lanzar excepción
            if (is_string($order)) {
                $decoded = json_decode($order, true);
                $errorMsg = $decoded['merchant_message'] ?? $decoded['user_message'] ?? 'Error desconocido';
                Log::error('Culqi order error', ['response' => $decoded]);
                return response()->json(['error' => $errorMsg], 422);
            }

            $orderId = is_object($order) ? $order->id : ($order['id'] ?? null);

            if (!$orderId) {
                Log::error('Culqi order: no ID returned', ['response' => $order]);
                return response()->json(['error' => 'No se pudo crear la orden de pago.'], 500);
            }

            return response()->json(['order_id' => $orderId]);
        } catch (\Exception $e) {
            Log::error('Culqi order creation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo iniciar el proceso de pago.'], 500);
        }
    }

    public function process(Request $request, CreateOrder $createOrder)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:1000',
            'customer_notes' => 'nullable|string|max:2000',
        ]);

        $cart = app(GetCart::class)->execute();

        if (empty($cart['cartItems'])) {
            return back()->with('error', 'Tu carrito está vacío.');
        }

        $totalInCents = (int) round($cart['total'] * 100);

        try {
            $culqi = new Culqi(['api_key' => config('culqi.secret_key')]);

            $charge = $culqi->Charges->create([
                'amount' => $totalInCents,
                'currency_code' => 'PEN',
                'email' => $validated['customer_email'],
                'source_id' => $validated['token'],
                'description' => 'Pedido en ' . config('app.name'),
                'metadata' => [
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                ],
            ]);

            if (is_string($charge)) {
                $decoded = json_decode($charge, true);
                throw new \Exception($decoded['merchant_message'] ?? $decoded['user_message'] ?? 'Charge failed');
            }
        } catch (\Exception $e) {
            Log::error('Culqi charge failed', [
                'error' => $e->getMessage(),
                'email' => $validated['customer_email'],
                'amount' => $totalInCents,
            ]);

            return back()
                ->withInput()
                ->with('error', 'El pago no pudo ser procesado. Por favor intenta nuevamente.');
        }

        $chargeId = is_object($charge) ? $charge->id : ($charge['id'] ?? null);

        $order = $this->createLocalOrder($validated, $cart, $chargeId, $createOrder);

        return redirect()->route('orders.show', $order)
            ->with('success', "¡Pago exitoso! Tu pedido {$order->order_number} ha sido confirmado.");
    }

    public function processYape(Request $request, CreateOrder $createOrder)
    {
        $validated = $request->validate([
            'culqi_order_id' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:1000',
            'customer_notes' => 'nullable|string|max:2000',
        ]);

        $cart = app(GetCart::class)->execute();

        if (empty($cart['cartItems'])) {
            return response()->json(['error' => 'Carrito vacío'], 422);
        }

        try {
            $culqi = new Culqi(['api_key' => config('culqi.secret_key')]);
            $culqiOrder = $culqi->Orders->get($validated['culqi_order_id']);

            if (is_string($culqiOrder)) {
                $culqiOrder = json_decode($culqiOrder);
            }

            $state = is_object($culqiOrder) ? $culqiOrder->state : ($culqiOrder['state'] ?? null);

            if ($state !== 'paid') {
                return response()->json(['error' => 'El pago con Yape no fue completado.'], 422);
            }

            $orderId = is_object($culqiOrder) ? $culqiOrder->id : ($culqiOrder['id'] ?? null);
        } catch (\Exception $e) {
            Log::error('Culqi Yape verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo verificar el pago.'], 500);
        }

        $order = $this->createLocalOrder($validated, $cart, $orderId, $createOrder);

        return response()->json([
            'success' => true,
            'redirect' => route('orders.show', $order),
        ]);
    }

    private function createLocalOrder(array $validated, array $cart, ?string $paymentReference, CreateOrder $createOrder)
    {
        $items = [];
        foreach ($cart['cartItems'] as $cartItem) {
            $items[] = [
                'product_id' => $cartItem['product']->id,
                'quantity' => $cartItem['quantity'],
            ];
        }

        $dto = new CreateOrderDTO(
            customerName: $validated['customer_name'],
            paymentMethod: 'culqi',
            paymentStatus: 'pagado',
            items: $items,
            customerEmail: $validated['customer_email'],
            customerPhone: $validated['customer_phone'],
            shippingAddress: $validated['shipping_address'],
            createdBy: 0,
            source: 'web',
            paymentReference: $paymentReference,
            customerNotes: $validated['customer_notes'] ?? null,
        );

        $order = $createOrder->execute($dto);

        session()->forget('cart');

        return $order;
    }
}
