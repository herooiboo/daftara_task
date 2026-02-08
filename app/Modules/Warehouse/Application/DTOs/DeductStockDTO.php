<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use Illuminate\Database\Eloquent\Model;

readonly class DeductStockDTO implements HasId, HasAmount, HasUserId, HasWarehouseId, HasInventoryId
{
    public function __construct(
        public Model $sourceStock,
        public float $amount,
        public int   $userId,
    ) {}

    public function getId(): int
    {
        return $this->sourceStock->id;
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
        return $this->sourceStock->warehouse_id;
    }

    public function getInventoryId(): int
    {
        return $this->sourceStock->inventory_id;
    }
}
