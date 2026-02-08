<?php

namespace App\Modules\Notifications\Infrastructure\Events;

use App\Modules\Warehouse\Application\DTOs\LowStockEventDataDTO;
use Illuminate\Foundation\Events\Dispatchable;

class LowStockDetected
{
    use Dispatchable;

    public function __construct(
        public readonly LowStockEventDataDTO $eventData,
    ) {}

    public function getWarehouseId(): int
    {
        return $this->eventData->warehouseId;
    }

    public function getInventoryItemId(): int
    {
        return $this->eventData->inventoryItemId;
    }

    public function getCurrentStock(): float
    {
        return $this->eventData->currentStock;
    }

    public function getThreshold(): float
    {
        return $this->eventData->threshold;
    }
}
