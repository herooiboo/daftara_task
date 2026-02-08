<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;

readonly class FindByWarehouseAndInventoryDTO implements HasWarehouseId, HasInventoryId
{
    public function __construct(
        public int $warehouseId,
        public int $inventoryId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            warehouseId: $data['warehouse_id'],
            inventoryId: $data['inventory_id'],
        );
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }
}
