<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;
use App\Modules\Warehouse\Domain\Exceptions\InventoryItemNotFoundException;

class ShowInventoryItemService
{
    public function __construct(
        protected InventoryItemRepositoryInterface $repository,
    ) {}

    public function handle(int $id): object
    {
        $item = $this->repository->findById($id);

        if (! $item) {
            throw new InventoryItemNotFoundException();
        }

        return $item;
    }
}
