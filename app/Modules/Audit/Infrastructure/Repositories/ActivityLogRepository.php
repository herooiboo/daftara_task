<?php

namespace App\Modules\Audit\Infrastructure\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Infrastructure\Repositories\BaseRepository;
use App\Modules\Audit\Application\Pipelines\FilterByCauserId;
use App\Modules\Audit\Application\Pipelines\FilterByDateFrom;
use App\Modules\Audit\Application\Pipelines\FilterByDateTo;
use App\Modules\Audit\Application\Pipelines\FilterByEvent;
use App\Modules\Audit\Application\Pipelines\FilterBySubjectType;
use App\Modules\Audit\Domain\Contracts\Filters\HasCauserId;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateFrom;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateTo;
use App\Modules\Audit\Domain\Contracts\Filters\HasEvent;
use App\Modules\Audit\Domain\Contracts\Filters\HasSubjectType;
use App\Modules\Audit\Domain\Contracts\Repositories\ActivityLogRepositoryInterface;
use Spatie\Activitylog\Models\Activity;

class ActivityLogRepository extends BaseRepository implements ActivityLogRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Activity());
    }

    public function getAllActivityLogsQuery(): mixed
    {
        return Activity::query()->with('causer')->latest();
    }

    public function getAllActivityLogs(
        HasSubjectType&HasCauserId&HasEvent&HasDateFrom&HasDateTo&HasPagination $filter
    ): mixed
    {
        $query = $this->getAllActivityLogsQuery();

        return $this->applyFilter($query, [
            new FilterBySubjectType($filter),
            new FilterByCauserId($filter),
            new FilterByEvent($filter),
            new FilterByDateFrom($filter),
            new FilterByDateTo($filter),
        ])->paginate($filter->getPerPage());
    }
}
