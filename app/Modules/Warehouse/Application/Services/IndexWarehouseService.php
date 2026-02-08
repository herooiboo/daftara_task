<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;

class IndexWarehouseService
{
    public function __construct(
        protected WarehouseRepositoryInterface $repository,
    ) {}

    public function handle(HasName&HasLocation&HasPagination $filter): mixed
    {
        return $this->repository->getAllWarehouses($filter);
    }
}
