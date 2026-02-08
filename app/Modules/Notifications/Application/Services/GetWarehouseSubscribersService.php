<?php

namespace App\Modules\Notifications\Application\Services;

use App\Modules\Notifications\Domain\Contracts\Repositories\WarehouseNotificationSubscriptionRepositoryInterface;

class GetWarehouseSubscribersService
{
    public function __construct(protected
        WarehouseNotificationSubscriptionRepositoryInterface $repository,
        )
    {
    }

    /**
     * @return \App\Modules\Notifications\Domain\Entities\WarehouseNotificationSubscription[]
     */
    public function handle(int $warehouseId): array
    {
        return $this->repository->getByWarehouseId($warehouseId);
    }
}
