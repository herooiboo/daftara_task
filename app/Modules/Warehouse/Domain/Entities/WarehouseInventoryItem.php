<?php

namespace App\Modules\Warehouse\Domain\Entities;

readonly class WarehouseInventoryItem
{
    public function __construct(
        public int $id,
        public int $warehouseId,
        public int $inventoryId,
        public float $quantity,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            warehouseId: $data['warehouse_id'],
            inventoryId: $data['inventory_id'],
            quantity: (float) $data['quantity'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'warehouse_id' => $this->warehouseId,
            'inventory_id' => $this->inventoryId,
            'quantity' => $this->quantity,
        ];
    }

    public function isLowStock(float $threshold): bool
    {
        return $this->quantity <= $threshold;
    }
}
