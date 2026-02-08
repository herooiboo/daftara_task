<?php

namespace App\Modules\Audit\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateTo;
use Carbon\Carbon;
use Closure;

class FilterByDateTo implements FilterInterface
{
    public function __construct(protected HasDateTo $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getDateTo() !== null) {
            $query->where('created_at', '<=', Carbon::parse($this->filter->getDateTo())->endOfDay());
        }

        return $next($query);
    }
}
