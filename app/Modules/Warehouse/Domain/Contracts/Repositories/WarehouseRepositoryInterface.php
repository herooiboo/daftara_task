<?php

namespace App\Modules\Warehouse\Domain\Contracts\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Domain\Contracts\HasToUpdateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;

interface WarehouseRepositoryInterface
{
    public function findById(int $id): ?object;

    public function getAll(): mixed;

    public function getAllWarehouses(HasName&HasLocation&HasPagination $filter): mixed;

    public function createWarehouse(HasToCreateArray $data): object;

    public function updateWarehouse(HasToUpdateArray&HasId $data): ?object;

    public function delete(int $id): bool;
}
