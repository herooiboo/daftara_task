<?php

namespace App\Modules\Warehouse\Application\DTOs;

readonly class LowStockEventDataDTO
{
    public function __construct(
        public int   $warehouseId,
        public int   $inventoryItemId,
        public float $currentStock,
        public float $threshold,
    ) {}
}
