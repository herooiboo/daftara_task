<?php

namespace App\Modules\Notifications\Application\Services;

use App\Modules\Notifications\Application\DTOs\UnsubscribeUsersFromWarehouseNotificationDTO;
use App\Modules\Notifications\Domain\Contracts\Repositories\WarehouseNotificationSubscriptionRepositoryInterface;

class UnsubscribeUsersFromWarehouseNotificationService
{
    public function __construct(
        protected WarehouseNotificationSubscriptionRepositoryInterface $repository,
    ) {}

    public function handle(UnsubscribeUsersFromWarehouseNotificationDTO $dto): int
    {
        return $this->repository->unsubscribeMultiple($dto);
    }
}
