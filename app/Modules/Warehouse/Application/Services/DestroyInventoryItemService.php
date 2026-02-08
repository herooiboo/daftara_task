<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Exceptions\InventoryItemNotFoundException;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;

class DestroyInventoryItemService
{
    public function __construct(
        protected InventoryItemRepository $repository,
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
