<?php

namespace App\Modules\Audit\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Audit\Domain\Contracts\Filters\HasEvent;
use Closure;

class FilterByEvent implements FilterInterface
{
    public function __construct(protected HasEvent $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getEvent() !== null) {
            $query->where('event', $this->filter->getEvent());
        }

        return $next($query);
    }
}
