<?php

namespace App\Modules\Warehouse\Domain\Contracts\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Domain\Contracts\HasToUpdateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use Illuminate\Database\Eloquent\Model;

interface WarehouseRepositoryInterface
{
    public function findById(int $id): ?Model;

    public function getAll(): mixed;

    public function getAllWarehouses(HasName&HasLocation&HasPagination $filter): mixed;

    public function createWarehouse(HasToCreateArray $data): Model;

    public function updateWarehouse(HasToUpdateArray&HasId $data): ?Model;

    public function delete(int $id): bool;
}
