<?php

namespace App\Modules\Warehouse\Domain\Entities;

use DateTimeImmutable;

readonly class StockTransfer
{
    public function __construct(
        public int $id,
        public int $sourceWarehouseId,
        public int $destinationWarehouseId,
        public int $inventoryId,
        public float $quantity,
        public int $performedBy,
        public DateTimeImmutable $createdAt,
    ) {}

    public static function fromArray(array $data): self
    {
        $createdAt = $data['created_at'];
        if (is_string($createdAt)) {
            $createdAt = new DateTimeImmutable($createdAt);
        } elseif ($createdAt instanceof \DateTime) {
            $createdAt = DateTimeImmutable::createFromMutable($createdAt);
        }

        return new self(
            id: $data['id'],
            sourceWarehouseId: $data['source_warehouse_id'],
            destinationWarehouseId: $data['destination_warehouse_id'],
            inventoryId: $data['inventory_id'],
            quantity: (float) $data['quantity'],
            performedBy: $data['performed_by'],
            createdAt: $createdAt,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'source_warehouse_id' => $this->sourceWarehouseId,
            'destination_warehouse_id' => $this->destinationWarehouseId,
            'inventory_id' => $this->inventoryId,
            'quantity' => $this->quantity,
            'performed_by' => $this->performedBy,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
