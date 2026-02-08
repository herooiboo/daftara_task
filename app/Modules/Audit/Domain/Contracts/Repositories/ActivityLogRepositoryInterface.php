<?php

namespace App\Modules\Audit\Domain\Contracts\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Modules\Audit\Domain\Contracts\Filters\HasCauserId;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateFrom;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateTo;
use App\Modules\Audit\Domain\Contracts\Filters\HasEvent;
use App\Modules\Audit\Domain\Contracts\Filters\HasSubjectType;

interface ActivityLogRepositoryInterface
{
    public function getAllActivityLogsQuery(): mixed;

    public function getAllActivityLogs(
        HasSubjectType&HasCauserId&HasEvent&HasDateFrom&HasDateTo&HasPagination $filter
    ): mixed;
}
