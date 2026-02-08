<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasToCreateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;

readonly class CreateStockTransferDTO implements HasToCreateArray, HasWarehouseId, HasInventoryId, HasAmount
{
    public function __construct(
        public int   $inventoryId,
        public int   $baseWarehouseId,
        public int   $targetWarehouseId,
        public float $amount,
        public int   $createdBy,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            inventoryId: $data['inventory_id'],
            baseWarehouseId: $data['base_warehouse_id'],
            targetWarehouseId: $data['target_warehouse_id'],
            amount: $data['amount'],
            createdBy: $data['created_by'],
        );
    }

    public function getWarehouseId(): ?int
    {
        return $this->baseWarehouseId;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function toCreateArray(): array
    {
        return [
            'inventory_id' => $this->inventoryId,
            'base_warehouse_id' => $this->baseWarehouseId,
            'target_warehouse_id' => $this->targetWarehouseId,
            'amount' => $this->amount,
            'created_by' => $this->createdBy,
        ];
    }
}
