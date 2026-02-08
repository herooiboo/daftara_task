<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;
use App\Modules\Warehouse\Domain\Exceptions\InventoryItemNotFoundException;

class DestroyInventoryItemService
{
    public function __construct(
        protected InventoryItemRepositoryInterface $repository,
    ) {}

    /**
     * @throws InventoryItemNotFoundException
     */
    public function handle(int $id): bool
    {
        $item = $this->repository->findById($id);

        if (! $item) {
            throw new InventoryItemNotFoundException();
        }

        return $this->repository->delete($id);
    }
}
