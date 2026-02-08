<?php

namespace App\Modules\Audit\Application\Services;

use App\Modules\Audit\Domain\Contracts\Filters\HasCauserId;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateFrom;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateTo;
use App\Modules\Audit\Domain\Contracts\Filters\HasEvent;
use App\Modules\Audit\Domain\Contracts\Filters\HasSubjectType;
use App\Modules\Audit\Domain\Contracts\Repositories\ActivityLogRepositoryInterface;

class GetActivityLogsService
{
    public function __construct(
        protected ActivityLogRepositoryInterface $repository,
    ) {}

    public function handle(
        HasSubjectType&HasCauserId&HasEvent&HasDateFrom&HasDateTo $filter
    ): mixed
    {
        return $this->repository->getAllActivityLogs($filter);
    }
}
