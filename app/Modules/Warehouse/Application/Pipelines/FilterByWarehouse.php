<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use Closure;

class FilterByWarehouse implements FilterInterface
{
    public function __construct(protected HasWarehouseId $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getWarehouseId() !== null) {
            $query->where('warehouse_id', $this->filter->getWarehouseId());
        }

        return $next($query);
    }
}
