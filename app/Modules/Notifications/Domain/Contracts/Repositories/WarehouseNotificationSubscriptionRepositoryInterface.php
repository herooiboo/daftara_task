<?php

namespace App\Modules\Notifications\Domain\Contracts\Repositories;

use App\Modules\Notifications\Domain\Contracts\Filters\HasUserIds;
use App\Modules\Notifications\Infrastructure\Models\WarehouseNotificationSubscription;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface WarehouseNotificationSubscriptionRepositoryInterface
{
    public function getByWarehouseId(int $warehouseId): Collection;

    public function subscribe(int $userId, int $warehouseId): WarehouseNotificationSubscription;

    public function unsubscribe(int $userId, int $warehouseId): bool;

    public function isSubscribed(int $userId, int $warehouseId): bool;

    public function subscribeMultiple(HasWarehouseId&HasUserIds $data): Collection;

    public function unsubscribeMultiple(HasWarehouseId&HasUserIds $data): int;
}
