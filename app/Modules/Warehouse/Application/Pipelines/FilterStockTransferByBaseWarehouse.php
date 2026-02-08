<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasBaseWarehouseId;
use Closure;

class FilterStockTransferByBaseWarehouse implements FilterInterface
{
    public function __construct(protected HasBaseWarehouseId $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getBaseWarehouseId() !== null) {
            $query->where('base_warehouse_id', $this->filter->getBaseWarehouseId());
        }

        return $next($query);
    }
}
