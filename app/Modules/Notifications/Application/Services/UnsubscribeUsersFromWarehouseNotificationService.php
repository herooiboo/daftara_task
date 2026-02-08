<?php

namespace App\Modules\Notifications\Application\Services;

use App\Modules\Notifications\Application\DTOs\UnsubscribeUsersFromWarehouseNotificationDTO;
use App\Modules\Notifications\Infrastructure\Repositories\WarehouseNotificationSubscriptionRepository;

class UnsubscribeUsersFromWarehouseNotificationService
{
    public function __construct(
        protected WarehouseNotificationSubscriptionRepository $repository,
    ) {}

    public function handle(UnsubscribeUsersFromWarehouseNotificationDTO $dto): int
    {
        return $this->repository->unsubscribeMultiple($dto);
    }
}