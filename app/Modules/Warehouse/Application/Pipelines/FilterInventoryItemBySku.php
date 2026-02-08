<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use Closure;

class FilterInventoryItemBySku implements FilterInterface
{
    public function __construct(protected HasSku $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getSku() !== null) {
            $query->where('SKU', 'like', '%' . $this->filter->getSku() . '%');
        }

        return $next($query);
    }
}
