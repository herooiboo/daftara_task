<?php

namespace App\Modules\Warehouse\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use Closure;

class FilterByLocation implements FilterInterface
{
    public function __construct(protected HasLocation $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getLocation() !== null) {
            $query->where('location', 'like', '%' . $this->filter->getLocation() . '%');
        }

        return $next($query);
    }
}
