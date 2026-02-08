<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\HasStockSource;

readonly class DeductStockDTO implements HasId, HasAmount, HasUserId, HasWarehouseId, HasInventoryId
{
    public function __construct(
        public HasStockSource $sourceStock,
        public float $amount,
        public int   $userId,
    ) {}

    public function getId(): int
    {
        return $this->sourceStock->getId();
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getWarehouseId(): ?int
    {
        return $this->sourceStock->getWarehouseId();
    }

    public function getInventoryId(): int
    {
        return $this->sourceStock->getInventoryId();
    }
}

