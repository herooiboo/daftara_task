<?php

namespace App\Modules\Notifications\Application\Services;

use App\Modules\Notifications\Application\DTOs\SubscribeUsersToWarehouseNotificationDTO;
use App\Modules\Notifications\Infrastructure\Repositories\WarehouseNotificationSubscriptionRepository;
use Illuminate\Support\Collection;

class SubscribeUsersToWarehouseNotificationService
{
    public function __construct(
        protected WarehouseNotificationSubscriptionRepository $repository,
    ) {}

    public function handle(SubscribeUsersToWarehouseNotificationDTO $dto): Collection
    {
        return $this->repository->subscribeMultiple($dto);
    }
}