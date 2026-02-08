<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\StoreWarehouseDTO;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Database\Eloquent\Model;

class StoreWarehouseService
{
    public function __construct(
        protected WarehouseRepository $repository,
    ) {}

    public function handle(StoreWarehouseDTO $dto): Model
    {
        return $this->repository->createWarehouse($dto);
    }
}
