<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;

readonly class AddStockToTargetDTO implements HasWarehouseId, HasInventoryId, HasAmount, HasUserId
{
    public function __construct(
        public int   $targetWarehouseId,
        public int   $inventoryId,
        public float $amount,
        public float $lowStockThreshold,
        public int   $userId,
    ) {}

    public function getWarehouseId(): ?int
    {
        return $this->targetWarehouseId;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getLowStockThreshold(): float
    {
        return $this->lowStockThreshold;
    }
}
