<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use App\Modules\Warehouse\Domain\Exceptions\InsufficientStockException;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseInventoryItemRepository;

class StockTransferOperationService
{
    public function __construct(
        protected WarehouseInventoryItemRepository $warehouseInventoryItemRepository,
    ) {}


    /**
     * @throws InsufficientStockException
     */
    public function deductFromSource(
        HasWarehouseId&HasInventoryId&HasAmount&HasUserId $data
    ): void
    {
        $this->warehouseInventoryItemRepository->validateStockAvailability($data);
        $this->warehouseInventoryItemRepository->deductStock($data);
    }

    public function addToTarget(
        HasWarehouseId&HasInventoryId&HasAmount&HasUserId $data,
        float                                             $lowStockThreshold
    ): void
    {
        $this->warehouseInventoryItemRepository->addStockToTarget($data, $lowStockThreshold);
    }
}
