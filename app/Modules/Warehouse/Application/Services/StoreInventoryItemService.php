<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\StoreInventoryItemDTO;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;
use Illuminate\Database\Eloquent\Model;

class StoreInventoryItemService
{
    public function __construct(
        protected InventoryItemRepository $repository,
    ) {}

    public function handle(StoreInventoryItemDTO $dto): Model
    {
        return $this->repository->createInventoryItem($dto);
    }
}
