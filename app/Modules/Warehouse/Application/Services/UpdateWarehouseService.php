<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\UpdateWarehouseDTO;
use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Database\Eloquent\Model;

class UpdateWarehouseService
{
    public function __construct(
        protected WarehouseRepository $repository,
    ) {}

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(UpdateWarehouseDTO $dto): Model
    {
        $warehouse = $this->repository->updateWarehouse($dto);

        if (! $warehouse) {
            throw new WarehouseNotFoundException();
        }

        return $warehouse;
    }
}
