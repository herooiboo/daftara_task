<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\StoreInventoryItemDTO;
use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;

class StoreInventoryItemService
{
    public function __construct(
        protected InventoryItemRepositoryInterface $repository,
    ) {}

    public function handle(StoreInventoryItemDTO $dto): object
    {
        return $this->repository->createInventoryItem($dto);
    }
}
