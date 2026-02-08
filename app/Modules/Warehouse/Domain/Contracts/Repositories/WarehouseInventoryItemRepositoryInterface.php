<?php

namespace App\Modules\Warehouse\Domain\Contracts\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Domain\Contracts\HasToUpdateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;

interface WarehouseInventoryItemRepositoryInterface
{
    public function getByWarehouseId(int $warehouseId): mixed;

    public function findByWarehouseAndInventory(
        HasWarehouseId&HasInventoryId $data
    ): ?object;

    public function validateStockAvailability(
        HasWarehouseId&HasInventoryId&HasAmount $data
    ): object;

    public function deductStock(
        HasId&HasAmount&HasUserId $data
    ): object;

    public function addStockToTarget(
        HasWarehouseId&HasInventoryId&HasAmount&HasUserId $data,
        float                                             $lowStockThreshold
    ): object;

    public function updateStock(
        HasToUpdateArray&HasId $data
    ): object;

    public function createWarehouseInventoryItem(
        HasToCreateArray $data
    ): object;

    public function getAllInventoryQuery(): mixed;

    public function getAllInventory(
        HasWarehouseId&HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter
    ): mixed;

    public function getWarehouseInventory(
        int $warehouseId,
        HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter
    ): mixed;
}
