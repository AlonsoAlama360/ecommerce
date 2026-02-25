<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $supplierIds = Supplier::where('is_active', true)->pluck('id')->toArray();

        if (empty($supplierIds)) {
            $supplierIds = Supplier::pluck('id')->toArray();
        }

        $statuses = ['pendiente', 'aprobado', 'en_transito', 'recibido', 'cancelado'];
        $statusWeights = [12, 10, 15, 50, 13];

        $notes = [
            null, null, null, null, null, null,
            'Reposición de stock mensual',
            'Pedido urgente por alta demanda',
            'Compra programada trimestral',
            'Productos nuevos para catálogo',
            'Reabastecimiento por temporada',
            'Promoción especial del proveedor',
            'Lote con descuento por volumen',
            'Compra de prueba - nuevo proveedor',
            'Stock mínimo alcanzado',
        ];

        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 90))->subHours(rand(8, 18))->subMinutes(rand(0, 59));
            $status = $this->weightedRandom($statuses, $statusWeights);

            $expectedDate = rand(0, 100) < 70
                ? $createdAt->copy()->addDays(rand(3, 15))->toDateString()
                : null;

            $receivedDate = null;
            if ($status === 'recibido') {
                $receivedDate = $expectedDate
                    ? Carbon::parse($expectedDate)->addDays(rand(-2, 3))->toDateString()
                    : $createdAt->copy()->addDays(rand(5, 20))->toDateString();
            }

            // 2-6 productos por compra
            $numItems = rand(2, 6);
            $selectedProducts = $products->random(min($numItems, $products->count()));

            $subtotal = 0;
            $itemsData = [];

            foreach ($selectedProducts as $product) {
                $qty = rand(5, 50);
                // Costo unitario: 40%-80% del precio de venta
                $basePrice = $product->sale_price ?? $product->price;
                $unitCost = round($basePrice * (rand(40, 80) / 100), 2);
                $lineTotal = round($unitCost * $qty, 2);
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'line_total' => $lineTotal,
                ];
            }

            $purchase = Purchase::create([
                'supplier_id' => $supplierIds[array_rand($supplierIds)],
                'status' => $status,
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total' => $subtotal,
                'expected_date' => $expectedDate,
                'received_date' => $receivedDate,
                'notes' => $notes[array_rand($notes)],
                'created_by' => 1,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($itemsData as $item) {
                $purchase->items()->create($item);
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
