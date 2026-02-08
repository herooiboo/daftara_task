<?php

namespace App\Modules\Audit\Application\Pipelines;

use App\Domain\Contracts\FilterInterface;
use App\Modules\Audit\Domain\Contracts\Filters\HasSubjectType;
use Closure;

class FilterBySubjectType implements FilterInterface
{
    public function __construct(protected HasSubjectType $filter) {}

    public function handle($query, Closure $next)
    {
        if ($this->filter->getSubjectType() !== null) {
            $query->where('subject_type', $this->filter->getSubjectType());
        }

        return $next($query);
    }
}
