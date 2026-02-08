<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\LowStockEventDataDTO;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseInventoryItemRepositoryInterface;

class LowStockCheckService
{
    public function __construct(
        protected WarehouseInventoryItemRepositoryInterface $warehouseInventoryItemRepository,
    ) {}

    public function checkLowStock(
        HasWarehouseId&HasInventoryId $data
    ): ?LowStockEventDataDTO
    {
        $stock = $this->warehouseInventoryItemRepository->findByWarehouseAndInventory($data);

        if (!$stock || $stock->low_stock_threshold === null || $stock->stock > $stock->low_stock_threshold) {
            return null;
        }

        return new LowStockEventDataDTO(
            warehouseId: $data->getWarehouseId(),
            inventoryItemId: $data->getInventoryId(),
            currentStock: $stock->stock,
            threshold: $stock->low_stock_threshold,
        );
    }
}
