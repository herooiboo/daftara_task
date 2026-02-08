<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasTargetWarehouseId;
use Closure;

class FilterStockTransferByTargetWarehouse implements FilterInterface
{
    public function __construct(protected HasTargetWarehouseId $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getTargetWarehouseId() !== null) {
            $query->where('target_warehouse_id', $this->filter->getTargetWarehouseId());
        }

        return $next($query);
    }
}
