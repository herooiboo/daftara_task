<?php

namespace App\Modules\Warehouse\Infrastructure\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Domain\Contracts\HasToUpdateArray;
use App\Infrastructure\Repositories\BaseRepository;
use App\Modules\Warehouse\Application\Pipelines\FilterByLocation;
use App\Modules\Warehouse\Application\Pipelines\FilterWarehouseByName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WarehouseRepository extends BaseRepository implements WarehouseRepositoryInterface
{
    public function __construct(Warehouse $model)
    {
        parent::__construct($model);
    }

    public function findById(int $id): ?Model
    {
        return $this->model->query()->find($id);
    }

    public function getAll(): Collection
    {
        return $this->model->query()->get();
    }

    public function getAllWarehouses(HasName&HasLocation&HasPagination $filter): mixed
    {
        $query = $this->model->query();

        return $this->applyFilter($query, [
            new FilterWarehouseByName($filter),
            new FilterByLocation($filter),
        ])->paginate($filter->getPerPage());
    }

    public function createWarehouse(HasToCreateArray $data): Model
    {
        return $this->model->query()->create($data->toCreateArray());
    }

    public function updateWarehouse(HasToUpdateArray&HasId $data): ?Model
    {
        $warehouse = $this->model->query()->find($data->getId());
        if (!$warehouse) {
            return null;
        }

        $warehouse->update($data->toUpdateArray());

        return $warehouse->fresh();
    }

}
