<?php

namespace App\Modules\Warehouse\Infrastructure\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Infrastructure\Repositories\BaseRepository;
use App\Modules\Warehouse\Application\Pipelines\FilterStockTransferByBaseWarehouse;
use App\Modules\Warehouse\Application\Pipelines\FilterStockTransferByInventory;
use App\Modules\Warehouse\Application\Pipelines\FilterStockTransferByTargetWarehouse;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasBaseWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasTargetWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\Repositories\StockTransferRepositoryInterface;
use App\Modules\Warehouse\Infrastructure\Models\StockTransfer;
use Illuminate\Database\Eloquent\Model;

class StockTransferRepository extends BaseRepository implements StockTransferRepositoryInterface
{
    public function __construct(StockTransfer $model)
    {
        parent::__construct($model);
    }

    public function createStockTransfer(HasToCreateArray $data): Model
    {
        return $this->model->query()->create($data->toCreateArray());
    }

    public function getAllStockTransfers(
        HasBaseWarehouseId&HasTargetWarehouseId&HasInventoryId&HasPagination $filter
    ): mixed
    {
        $query = $this->model->query()
            ->with(['inventoryItem', 'baseWarehouse', 'targetWarehouse', 'creator']);

        return $this->applyFilter($query, [
            new FilterStockTransferByBaseWarehouse($filter),
            new FilterStockTransferByTargetWarehouse($filter),
            new FilterStockTransferByInventory($filter),
        ])->orderBy('created_at', 'desc')
            ->paginate($filter->getPerPage());
    }
}

