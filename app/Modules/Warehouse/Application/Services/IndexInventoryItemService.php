<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;

class IndexInventoryItemService
{
    public function __construct(
        protected InventoryItemRepository $repository,
    ) {}

    public function handle(HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter): mixed
    {
        return $this->repository->getAllInventoryItems($filter);
    }
}
