<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\UpdateInventoryItemDTO;
use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;
use App\Modules\Warehouse\Domain\Exceptions\InventoryItemNotFoundException;

class UpdateInventoryItemService
{
    public function __construct(
        protected InventoryItemRepositoryInterface $repository,
    ) {}

    /**
     * @throws InventoryItemNotFoundException
     */
    public function handle(UpdateInventoryItemDTO $dto): object
    {
        $item = $this->repository->updateInventoryItem($dto);

        if (! $item) {
            throw new InventoryItemNotFoundException();
        }

        return $item;
    }
}
