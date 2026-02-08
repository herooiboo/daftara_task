<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Database\Eloquent\Model;

class ShowWarehouseService
{
    public function __construct(
        protected WarehouseRepository $repository,
    ) {}

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(int $id): Model
    {
        $warehouse = $this->repository->findById($id);

        if (! $warehouse) {
            throw new WarehouseNotFoundException();
        }

        return $warehouse;
    }
}
