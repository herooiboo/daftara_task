<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;
use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;

class DestroyWarehouseService
{
    public function __construct(
        protected WarehouseRepositoryInterface $repository,
    ) {}

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(int $id): bool
    {
        $warehouse = $this->repository->findById($id);

        if (! $warehouse) {
            throw new WarehouseNotFoundException();
        }

        return $this->repository->delete($id);
    }
}
