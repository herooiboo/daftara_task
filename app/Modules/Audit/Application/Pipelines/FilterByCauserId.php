<?php

namespace App\Modules\Audit\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Audit\Domain\Contracts\Filters\HasCauserId;
use Closure;

class FilterByCauserId implements FilterInterface
{
    public function __construct(protected HasCauserId $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getCauserId() !== null) {
            $query->where('causer_id', $this->filter->getCauserId());
        }

        return $next($query);
    }
}
