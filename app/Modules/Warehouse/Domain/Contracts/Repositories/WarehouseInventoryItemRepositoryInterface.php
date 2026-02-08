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
use Illuminate\Database\Eloquent\Model;

interface WarehouseInventoryItemRepositoryInterface
{
    public function getByWarehouseId(int $warehouseId): mixed;

    public function findByWarehouseAndInventory(
        HasWarehouseId&HasInventoryId $data
    ): ?Model;

    public function validateStockAvailability(
        HasWarehouseId&HasInventoryId&HasAmount $data
    ): Model;

    public function deductStock(
        HasId&HasAmount&HasUserId $data
    ): Model;

    public function addStockToTarget(
        HasWarehouseId&HasInventoryId&HasAmount&HasUserId $data,
        float                                             $lowStockThreshold
    ): Model;

    public function updateStock(
        HasToUpdateArray&HasId $data
    ): Model;

    public function createWarehouseInventoryItem(
        HasToCreateArray $data
    ): Model;

    public function getAllInventoryQuery(): mixed;

    public function getAllInventory(
        HasWarehouseId&HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter
    ): mixed;

    public function getWarehouseInventory(
        int $warehouseId,
        HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter
    ): mixed;
}
