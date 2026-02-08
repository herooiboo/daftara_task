<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use Closure;

class FilterInventoryItemByPriceMin implements FilterInterface
{
    public function __construct(protected HasPriceMin $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getPriceMin() !== null) {
            $query->where('price', '>=', $this->filter->getPriceMin());
        }

        return $next($query);
    }
}
