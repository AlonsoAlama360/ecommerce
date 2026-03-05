<?php

namespace App\Application\Kardex\UseCases;

use App\Application\Kardex\DTOs\AdjustStockDTO;
use App\Models\Product;
use App\Services\StockService;

class AdjustStock
{
    public function execute(AdjustStockDTO $dto): array
    {
        $product = Product::findOrFail($dto->productId);

        StockService::adjust($product, $dto->newStock, $dto->notes);

        return [
            'product' => $product,
            'newStock' => $dto->newStock,
        ];
    }
}
