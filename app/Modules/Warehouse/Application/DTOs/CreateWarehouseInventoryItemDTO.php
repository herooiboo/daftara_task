<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasToCreateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;

readonly class CreateWarehouseInventoryItemDTO implements HasWarehouseId, HasInventoryId, HasAmount, HasUserId, HasToCreateArray
{
    public function __construct(
        public int    $warehouseId,
        public int    $inventoryId,
        public float  $stock,
        public ?float $lowStockThreshold,
        public int    $userId,
    ) {}

    public function getWarehouseId(): ?int
    {
        return $this->warehouseId;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getAmount(): float
    {
        return $this->stock;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function toCreateArray(): array
    {
        return [
            'inventory_id' => $this->inventoryId,
            'warehouse_id' => $this->warehouseId,
            'stock' => $this->stock,
            'low_stock_threshold' => $this->lowStockThreshold,
            'last_updated_by' => $this->userId,
        ];
    }
}
