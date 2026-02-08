<?php

namespace App\Modules\Notifications\Domain\Entities;

readonly class WarehouseNotificationSubscription
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $warehouseId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            userId: $data['user_id'],
            warehouseId: $data['warehouse_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'warehouse_id' => $this->warehouseId,
        ];
    }
}
