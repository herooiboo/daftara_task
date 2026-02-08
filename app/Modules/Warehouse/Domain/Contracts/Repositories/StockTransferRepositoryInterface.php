<?php

namespace App\Modules\Warehouse\Domain\Contracts\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasBaseWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasTargetWarehouseId;

interface StockTransferRepositoryInterface
{
    public function createStockTransfer(HasToCreateArray $data): object;

    public function getAllStockTransfers(
        HasBaseWarehouseId&HasTargetWarehouseId&HasInventoryId&HasPagination $filter
    ): mixed;
}
