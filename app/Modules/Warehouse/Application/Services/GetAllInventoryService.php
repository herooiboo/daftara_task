<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseInventoryItemRepository;

class GetAllInventoryService
{
    public function __construct(
        protected WarehouseInventoryItemRepository $repository,
    ) {}

    public function handle(
        HasWarehouseId&HasName&HasSku&HasPriceMin&HasPriceMax $filter
    ): mixed
    {
        return $this->repository->getAllInventory($filter);
    }
}
