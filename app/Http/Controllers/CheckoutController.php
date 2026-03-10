<?php

namespace App\Http\Controllers;

use App\Application\Cart\UseCases\GetCart;
use App\Application\Order\DTOs\CreateOrderDTO;
use App\Application\Order\UseCases\CreateOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
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
            'shippingAgencies' => \App\Models\ShippingAgency::where('is_active', true)->with(['addresses' => fn($q) => $q->where('is_active', true)->orderBy('address')])->orderBy('name')->get(),
            'shippingMode' => \App\Models\SiteSetting::get('shipping_mode', 'agency'),
        ]);
    }

    public function createCulqiOrder(Request $request)
    {
        $cart = app(GetCart::class)->execute();

        if (empty($cart['cartItems'])) {
            return response()->json(['error' => 'Carrito vacío'], 422);
        }

        // Validate stock before initiating payment
        $stockErrors = $this->validateStock($cart['cartItems']);
        if (!empty($stockErrors)) {
            return response()->json(['error' => implode(' ', $stockErrors)], 422);
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
                'confirm' => false,
                'expiration_date' => now()->addHours(1)->timestamp,
            ]);

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
        $shippingMode = SiteSetting::get('shipping_mode', 'agency');
        $method = $shippingMode === 'both' ? $request->input('shipping_method', 'agency') : $shippingMode;
        $wantsAgency = $method === 'agency';

        $validated = $request->validate([
            'token' => 'required|string',
            'culqi_order_id' => 'nullable|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => ($wantsAgency ? 'nullable' : 'required') . '|string|max:1000',
            'shipping_agency' => ($wantsAgency ? 'required' : 'nullable') . '|string|max:255',
            'shipping_agency_address' => ($wantsAgency ? 'required' : 'nullable') . '|string|max:500',
            'shipping_method' => $shippingMode === 'both' ? 'required|in:agency,address' : 'nullable',
            'customer_notes' => 'nullable|string|max:2000',
        ]);

        // Idempotency: check if this token was already processed
        $existingOrder = Order::where('payment_reference', $validated['token'])->first();
        if ($existingOrder) {
            session()->forget('cart');
            return redirect()->route('orders.show', $existingOrder)
                ->with('success', "Tu pedido {$existingOrder->order_number} ya fue procesado.");
        }

        $cart = app(GetCart::class)->execute();

        if (empty($cart['cartItems'])) {
            return back()->with('error', 'Tu carrito está vacío.');
        }

        // Validate stock before charging
        $stockErrors = $this->validateStock($cart['cartItems']);
        if (!empty($stockErrors)) {
            return back()->withInput()->with('error', implode(' ', $stockErrors));
        }

        $totalInCents = (int) round($cart['total'] * 100);

        try {
            $culqi = new Culqi(['api_key' => config('culqi.secret_key')]);

            $nameParts = explode(' ', $validated['customer_name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $charge = $culqi->Charges->create([
                'amount' => $totalInCents,
                'currency_code' => 'PEN',
                'email' => $validated['customer_email'],
                'source_id' => $validated['token'],
                'description' => 'Pedido en ' . config('app.name'),
                'antifraud_details' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['customer_email'],
                    'phone_number' => $validated['customer_phone'],
                    'address' => $validated['shipping_address'],
                    'address_city' => 'Lima',
                    'country_code' => 'PE',
                ],
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

        // Clean up the pending Culqi order so it doesn't stay as "pendiente" in the panel
        if (!empty($validated['culqi_order_id'])) {
            try {
                $culqi->Orders->delete($validated['culqi_order_id']);
            } catch (\Exception $e) {
                Log::info('Could not delete Culqi order', [
                    'order_id' => $validated['culqi_order_id'],
                    'reason' => $e->getMessage(),
                ]);
            }
        }

        $order = $this->createLocalOrder($validated, $cart, $chargeId, $createOrder);

        return redirect()->route('orders.show', $order)
            ->with('success', "¡Pago exitoso! Tu pedido {$order->order_number} ha sido confirmado.");
    }

    public function processYape(Request $request, CreateOrder $createOrder)
    {
        $shippingMode = SiteSetting::get('shipping_mode', 'agency');
        $method = $shippingMode === 'both' ? $request->input('shipping_method', 'agency') : $shippingMode;
        $wantsAgency = $method === 'agency';

        $validated = $request->validate([
            'culqi_order_id' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => ($wantsAgency ? 'nullable' : 'required') . '|string|max:1000',
            'shipping_agency' => ($wantsAgency ? 'required' : 'nullable') . '|string|max:255',
            'shipping_agency_address' => ($wantsAgency ? 'required' : 'nullable') . '|string|max:500',
            'shipping_method' => $shippingMode === 'both' ? 'required|in:agency,address' : 'nullable',
            'customer_notes' => 'nullable|string|max:2000',
        ]);

        // Idempotency: check if this Culqi order was already processed
        $existingOrder = Order::where('payment_reference', $validated['culqi_order_id'])->first();
        if ($existingOrder) {
            session()->forget('cart');
            return response()->json([
                'success' => true,
                'redirect' => route('orders.show', $existingOrder) . '?payment_success=1',
            ]);
        }

        $cart = app(GetCart::class)->execute();

        if (empty($cart['cartItems'])) {
            return response()->json(['error' => 'Carrito vacío'], 422);
        }

        // Validate stock before confirming
        $stockErrors = $this->validateStock($cart['cartItems']);
        if (!empty($stockErrors)) {
            return response()->json(['error' => implode(' ', $stockErrors)], 422);
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
            'redirect' => route('orders.show', $order) . '?payment_success=1',
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
            shippingAgency: $validated['shipping_agency'],
            shippingAgencyAddress: $validated['shipping_agency_address'],
            createdBy: 0,
            source: 'web',
            paymentReference: $paymentReference,
            customerNotes: $validated['customer_notes'] ?? null,
        );

        $order = $createOrder->execute($dto);

        session()->forget('cart');

        return $order;
    }

    /**
     * Check that all cart items have sufficient stock.
     * Returns an array of error messages (empty if all OK).
     */
    private function validateStock(array $cartItems): array
    {
        $errors = [];

        foreach ($cartItems as $item) {
            $product = Product::find($item['product']->id);

            if (!$product || !$product->is_active) {
                $errors[] = "\"{$item['product']->name}\" ya no está disponible.";
                continue;
            }

            if ($product->stock < $item['quantity']) {
                if ($product->stock === 0) {
                    $errors[] = "\"{$product->name}\" se agotó.";
                } else {
                    $errors[] = "\"{$product->name}\" solo tiene {$product->stock} unidades disponibles.";
                }
            }
        }

        return $errors;
    }
}
