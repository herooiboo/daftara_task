<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use Closure;

class FilterStockTransferByInventory implements FilterInterface
{
    public function __construct(protected HasInventoryId $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getInventoryId() !== null) {
            $query->where('inventory_id', $this->filter->getInventoryId());
        }

        return $next($query);
    }
}
