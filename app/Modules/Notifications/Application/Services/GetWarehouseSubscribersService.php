<?php

namespace App\Modules\Notifications\Application\Services;

use App\Modules\Notifications\Infrastructure\Repositories\WarehouseNotificationSubscriptionRepository;
use Illuminate\Support\Collection;

class GetWarehouseSubscribersService
{
    public function __construct(
        protected WarehouseNotificationSubscriptionRepository $repository,
    ) {}

    public function handle(int $warehouseId): Collection
    {
        return $this->repository->getByWarehouseId($warehouseId);
    }
}
