<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasToUpdateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasStock;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;

readonly class UpdateStockDTO implements HasId, HasStock, HasUserId, HasToUpdateArray
{
    public function __construct(
        public int   $id,
        public float $stock,
        public int   $userId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            stock: $data['stock'],
            userId: $data['user_id'],
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStock(): float
    {
        return $this->stock;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function toUpdateArray(): array
    {
        return [
            'stock' => $this->stock,
            'last_updated_by' => $this->userId,
        ];
    }
}
