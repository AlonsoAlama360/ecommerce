<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $userIds = User::pluck('id')->toArray();

        $statuses = ['pendiente', 'confirmado', 'en_preparacion', 'enviado', 'entregado', 'cancelado'];
        $statusWeights = [15, 10, 10, 15, 40, 10]; // entregado más frecuente

        $paymentMethods = ['efectivo', 'transferencia', 'yape_plin', 'tarjeta'];
        $paymentMethodWeights = [20, 15, 40, 25]; // yape/plin más popular

        $paymentStatuses = ['pendiente', 'pagado', 'fallido'];

        $sources = ['web', 'admin'];

        $firstNames = [
            'María', 'Juan', 'Ana', 'Carlos', 'Rosa', 'Luis', 'Carmen', 'José', 'Patricia', 'Miguel',
            'Sofía', 'Diego', 'Lucía', 'Pedro', 'Elena', 'Ricardo', 'Gabriela', 'Fernando', 'Valentina', 'Andrés',
            'Isabel', 'Roberto', 'Daniela', 'Alejandro', 'Camila', 'Jorge', 'Natalia', 'Francisco', 'Mariana', 'Eduardo',
        ];

        $lastNames = [
            'García', 'Rodríguez', 'Martínez', 'López', 'Hernández', 'González', 'Pérez', 'Sánchez', 'Ramírez', 'Torres',
            'Flores', 'Rivera', 'Gómez', 'Díaz', 'Cruz', 'Morales', 'Reyes', 'Gutiérrez', 'Ortiz', 'Ruiz',
            'Vargas', 'Mendoza', 'Castillo', 'Rojas', 'Medina', 'Chávez', 'Paredes', 'Quispe', 'Huamán', 'Silva',
        ];

        $districts = [
            'Miraflores', 'San Isidro', 'Surco', 'La Molina', 'San Borja', 'Barranco',
            'Jesús María', 'Lince', 'Magdalena', 'Pueblo Libre', 'San Miguel', 'Breña',
            'Chorrillos', 'Lima Cercado', 'Rímac', 'Los Olivos', 'San Martín de Porres',
            'Comas', 'Independencia', 'Ate', 'Santa Anita', 'Surquillo', 'San Juan de Lurigancho',
        ];

        $streets = [
            'Av. Arequipa', 'Jr. de la Unión', 'Av. Javier Prado', 'Calle Los Pinos', 'Av. Primavera',
            'Jr. Cusco', 'Av. Brasil', 'Calle Las Flores', 'Av. Benavides', 'Jr. Junín',
            'Av. La Marina', 'Calle Los Olivos', 'Av. Universitaria', 'Jr. Lampa', 'Av. Angamos',
        ];

        $adminNotes = [
            null, null, null, null, null, null, null,
            'Cliente frecuente', 'Envío urgente', 'Pedido por teléfono', 'Regalo - envolver',
            'Entregar en recepción', 'Cliente VIP', 'Pago verificado', 'Consultar disponibilidad',
        ];

        for ($i = 0; $i < 100; $i++) {
            // Fecha aleatoria en los últimos 60 días
            $createdAt = Carbon::now()->subDays(rand(0, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $date = $createdAt->format('Ymd');

            $firstName = $firstNames[array_rand($firstNames)];
            $lastName1 = $lastNames[array_rand($lastNames)];
            $lastName2 = $lastNames[array_rand($lastNames)];
            $customerName = "$firstName $lastName1 $lastName2";

            $status = $this->weightedRandom($statuses, $statusWeights);
            $paymentMethod = $this->weightedRandom($paymentMethods, $paymentMethodWeights);
            $source = $sources[array_rand($sources)];

            // Payment status lógico según estado de la orden
            if ($status === 'entregado') {
                $paymentStatus = 'pagado';
            } elseif ($status === 'cancelado') {
                $paymentStatus = rand(0, 1) ? 'fallido' : 'pendiente';
            } elseif (in_array($status, ['enviado', 'en_preparacion', 'confirmado'])) {
                $paymentStatus = rand(0, 100) < 70 ? 'pagado' : 'pendiente';
            } else {
                $paymentStatus = rand(0, 100) < 30 ? 'pagado' : 'pendiente';
            }

            $hasPhone = rand(0, 100) < 80;
            $hasEmail = rand(0, 100) < 60;
            $hasAddress = rand(0, 100) < 75;

            $phone = $hasPhone ? '9' . rand(10000000, 99999999) : null;
            $email = $hasEmail ? strtolower(str_replace(' ', '', $firstName)) . rand(10, 999) . '@' . ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com'][rand(0, 3)] : null;
            $address = $hasAddress
                ? $streets[array_rand($streets)] . ' ' . rand(100, 2500) . ', ' . $districts[array_rand($districts)]
                : null;

            $userId = rand(0, 100) < 60 ? $userIds[array_rand($userIds)] : null;

            // Seleccionar 1-5 productos aleatorios
            $numItems = rand(1, 5);
            $selectedProducts = $products->random(min($numItems, $products->count()));

            $subtotal = 0;
            $itemsData = [];

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 3);
                $price = $product->sale_price ?? $product->price;
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ];
            }

            $discountAmount = rand(0, 100) < 20 ? round($subtotal * (rand(5, 15) / 100), 2) : 0;
            $shippingCost = rand(0, 100) < 40 ? [10, 15, 20, 25, 30][rand(0, 4)] : 0;
            $total = $subtotal - $discountAmount + $shippingCost;

            $order = Order::create([
                'user_id' => $userId,
                'source' => $source,
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'customer_name' => $customerName,
                'customer_phone' => $phone,
                'customer_email' => $email,
                'shipping_address' => $address,
                'admin_notes' => $adminNotes[array_rand($adminNotes)],
                'created_by' => $source === 'admin' ? 1 : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }
        }
    }

    private function weightedRandom(array $items, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $rand = rand(1, $totalWeight);
        $cumulative = 0;

        foreach ($items as $i => $item) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $item;
            }
        }

        return end($items);
    }
}
