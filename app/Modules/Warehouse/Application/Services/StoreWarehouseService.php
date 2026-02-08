<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\StoreWarehouseDTO;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;

class StoreWarehouseService
{
    public function __construct(
        protected WarehouseRepositoryInterface $repository,
    ) {}

    public function handle(StoreWarehouseDTO $dto): object
    {
        return $this->repository->createWarehouse($dto);
    }
}
