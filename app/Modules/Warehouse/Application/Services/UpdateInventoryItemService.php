<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\UpdateInventoryItemDTO;
use App\Modules\Warehouse\Domain\Exceptions\InventoryItemNotFoundException;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;
use Illuminate\Database\Eloquent\Model;

class UpdateInventoryItemService
{
    public function __construct(
        protected InventoryItemRepository $repository,
    ) {}

    /**
     * @throws InventoryItemNotFoundException
     */
    public function handle(UpdateInventoryItemDTO $dto): Model
    {
        $item = $this->repository->updateInventoryItem($dto);

        if (! $item) {
            throw new InventoryItemNotFoundException();
        }

        return $item;
    }
}
