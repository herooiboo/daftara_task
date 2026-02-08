<?php

namespace App\Modules\Notifications\Application\DTOs;

use App\Modules\Notifications\Domain\Contracts\Filters\HasUserIds;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;

readonly class UnsubscribeUsersFromWarehouseNotificationDTO implements HasWarehouseId, HasUserIds
{
    public function __construct(
        public array $userIds,
        public int $warehouseId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userIds: $data['user_ids'],
            warehouseId: $data['warehouse_id'],
        );
    }

    public function getWarehouseId(): ?int
    {
        return $this->warehouseId;
    }

    public function getUserIds(): array
    {
        return $this->userIds;
    }
}