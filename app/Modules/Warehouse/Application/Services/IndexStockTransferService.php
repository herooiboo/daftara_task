<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasBaseWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasTargetWarehouseId;
use App\Modules\Warehouse\Infrastructure\Repositories\StockTransferRepository;

class IndexStockTransferService
{
    public function __construct(
        protected StockTransferRepository $repository,
    ) {}

    public function handle(
        HasBaseWarehouseId&HasTargetWarehouseId&HasInventoryId&HasPagination $filter
    ): mixed
    {
        return $this->repository->getAllStockTransfers($filter);
    }
}
