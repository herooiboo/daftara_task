<?php

namespace App\Modules\Notifications\Application\Services;

use App\Modules\Notifications\Application\DTOs\SubscribeUsersToWarehouseNotificationDTO;
use App\Modules\Notifications\Domain\Contracts\Repositories\WarehouseNotificationSubscriptionRepositoryInterface;

class SubscribeUsersToWarehouseNotificationService
{
    public function __construct(
        protected WarehouseNotificationSubscriptionRepositoryInterface $repository,
    ) {}

    /**
     * @return \App\Modules\Notifications\Domain\Entities\WarehouseNotificationSubscription[]
     */
    public function handle(SubscribeUsersToWarehouseNotificationDTO $dto): array
    {
        return $this->repository->subscribeMultiple($dto);
    }
}
