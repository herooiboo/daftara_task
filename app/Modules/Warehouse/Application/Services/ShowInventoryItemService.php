<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Exceptions\InventoryItemNotFoundException;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;
use Illuminate\Database\Eloquent\Model;

class ShowInventoryItemService
{
    public function __construct(
        protected InventoryItemRepository $repository,
    ) {}

    public function handle(int $id): Model
    {
        $item = $this->repository->findById($id);

        if (! $item) {
            throw new InventoryItemNotFoundException();
        }

        return $item;
    }
}
