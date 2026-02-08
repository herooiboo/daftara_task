<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use Closure;

class FilterInventoryItemByName implements FilterInterface
{
    public function __construct(protected HasName $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getName() !== null) {
            $query->where('name', 'like', '%' . $this->filter->getName() . '%');
        }

        return $next($query);
    }
}
