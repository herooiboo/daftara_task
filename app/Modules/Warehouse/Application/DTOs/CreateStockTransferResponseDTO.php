<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Modules\Warehouse\Infrastructure\Models\StockTransfer;

readonly class CreateStockTransferResponseDTO
{
    public function __construct(
        public StockTransfer         $transfer,
        public bool                  $isLowStock,
        public ?LowStockEventDataDTO $lowStockEventData = null,
    ) {}
}
