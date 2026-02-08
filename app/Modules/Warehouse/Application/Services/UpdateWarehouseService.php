<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\UpdateWarehouseDTO;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;
use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;

class UpdateWarehouseService
{
    public function __construct(
        protected WarehouseRepositoryInterface $repository,
    ) {}

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(UpdateWarehouseDTO $dto): object
    {
        $warehouse = $this->repository->updateWarehouse($dto);

        if (! $warehouse) {
            throw new WarehouseNotFoundException();
        }

        return $warehouse;
    }
}
