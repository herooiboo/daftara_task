<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseInventoryItemRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Support\Facades\Cache;

class GetWarehouseInventoryService
{
    public function __construct(
        protected WarehouseInventoryItemRepository $repository,
        protected WarehouseRepository $warehouseRepository,
    ) {}

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(
        int $warehouseId,
        HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter
    ): mixed
    {
        $warehouse = $this->warehouseRepository->findById($warehouseId);

        if (! $warehouse) {
            throw new WarehouseNotFoundException();
        }

        $hasFilters = $filter->getName() !== null
            || $filter->getSku() !== null
            || $filter->getPriceMin() !== null
            || $filter->getPriceMax() !== null;

        if ($hasFilters) {
            return $this->repository->getWarehouseInventory($warehouseId, $filter);
        }

        return Cache::remember(
            "warehouse_{$warehouseId}_inventory",
            now()->addMinutes(60),
            fn () => $this->repository->getByWarehouseId($warehouseId)
        );
    }
}
