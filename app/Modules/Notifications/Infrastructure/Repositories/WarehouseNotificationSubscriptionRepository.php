<?php

namespace App\Modules\Notifications\Infrastructure\Repositories;

use App\Modules\Notifications\Domain\Contracts\Filters\HasUserIds;
use App\Modules\Notifications\Domain\Contracts\Repositories\WarehouseNotificationSubscriptionRepositoryInterface;
use App\Modules\Notifications\Infrastructure\Models\WarehouseNotificationSubscription;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use Dust\Base\Repository;
use Illuminate\Support\Collection;

class WarehouseNotificationSubscriptionRepository extends Repository implements WarehouseNotificationSubscriptionRepositoryInterface
{
    public function __construct(WarehouseNotificationSubscription $model)
    {
        parent::__construct($model);
    }

    public function getByWarehouseId(int $warehouseId): Collection
    {
        return $this->model->query()
            ->where('warehouse_id', $warehouseId)
            ->with('user')
            ->get();
    }

    public function subscribe(int $userId, int $warehouseId): WarehouseNotificationSubscription
    {
        return $this->model->query()->create([
            'user_id' => $userId,
            'warehouse_id' => $warehouseId,
        ]);
    }

    public function unsubscribe(int $userId, int $warehouseId): bool
    {
        return (bool) $this->model->query()
            ->where('user_id', $userId)
            ->where('warehouse_id', $warehouseId)
            ->delete();
    }

    public function isSubscribed(int $userId, int $warehouseId): bool
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('warehouse_id', $warehouseId)
            ->exists();
    }

    public function subscribeMultiple(HasWarehouseId&HasUserIds $data): Collection
    {
        $userIds = $data->getUserIds();
        $warehouseId = $data->getWarehouseId();
        
        $subscriptionsData = array_map(function ($userId) use ($warehouseId) {
            return [
                'user_id' => $userId,
                'warehouse_id' => $warehouseId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $userIds);
        
        $this->model->query()->insertOrIgnore($subscriptionsData);
        
        return $this->model->query()
            ->where('warehouse_id', $warehouseId)
            ->whereIn('user_id', $userIds)
            ->get();
    }

    public function unsubscribeMultiple(HasWarehouseId&HasUserIds $data): int
    {
        return $this->model->query()
            ->where('warehouse_id', $data->getWarehouseId())
            ->whereIn('user_id', $data->getUserIds())
            ->delete();
    }
}
