<?php

namespace App\Modules\Audit\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateFrom;
use Carbon\Carbon;
use Closure;

class FilterByDateFrom implements FilterInterface
{
    public function __construct(protected HasDateFrom $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getDateFrom() !== null) {
            $query->where('created_at', '>=', Carbon::parse($this->filter->getDateFrom())->startOfDay());
        }

        return $next($query);
    }
}
