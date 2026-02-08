<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;

class DestroyWarehouseService
{
    public function __construct(
        protected WarehouseRepository $repository,
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
