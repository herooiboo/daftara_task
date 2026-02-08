<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use Closure;

class FilterByPriceMax implements FilterInterface
{
    public function __construct(protected HasPriceMax $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getPriceMax() !== null) {
            $query->whereHas('inventoryItem', function ($q) {
                $q->where('price', '<=', $this->filter->getPriceMax());
            });
        }

        return $next($query);
    }
}
