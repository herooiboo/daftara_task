<?php

namespace App\Modules\Notifications\Domain\Contracts\Repositories;

use App\Modules\Notifications\Domain\Contracts\Filters\HasUserIds;
use App\Modules\Notifications\Domain\Entities\WarehouseNotificationSubscription;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;

interface WarehouseNotificationSubscriptionRepositoryInterface
{
    /**
     * @return WarehouseNotificationSubscription[]
     */
    public function getByWarehouseId(int $warehouseId): array;

    public function subscribe(int $userId, int $warehouseId): WarehouseNotificationSubscription;

    public function unsubscribe(int $userId, int $warehouseId): bool;

    public function isSubscribed(int $userId, int $warehouseId): bool;

    /**
     * @return WarehouseNotificationSubscription[]
     */
    public function subscribeMultiple(HasWarehouseId&HasUserIds $data): array;

    public function unsubscribeMultiple(HasWarehouseId&HasUserIds $data): int;
}
