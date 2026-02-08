<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;

class IndexInventoryItemService
{
    public function __construct(
        protected InventoryItemRepositoryInterface $repository,
    ) {}

    public function handle(HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter): mixed
    {
        return $this->repository->getAllInventoryItems($filter);
    }
}
