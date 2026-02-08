<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;
use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;

class ShowWarehouseService
{
    public function __construct(
        protected WarehouseRepositoryInterface $repository,
    ) {}

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(int $id): object
    {
        $warehouse = $this->repository->findById($id);

        if (! $warehouse) {
            throw new WarehouseNotFoundException();
        }

        return $warehouse;
    }
}
