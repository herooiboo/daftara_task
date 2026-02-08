<?php

namespace App\Modules\Warehouse\Infrastructure\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Domain\Contracts\HasToUpdateArray;
use App\Infrastructure\Repositories\BaseRepository;
use App\Modules\Warehouse\Application\Pipelines\FilterInventoryItemByName;
use App\Modules\Warehouse\Application\Pipelines\FilterInventoryItemByPriceMax;
use App\Modules\Warehouse\Application\Pipelines\FilterInventoryItemByPriceMin;
use App\Modules\Warehouse\Application\Pipelines\FilterInventoryItemBySku;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use Illuminate\Support\Collection;

class InventoryItemRepository extends BaseRepository implements InventoryItemRepositoryInterface
{
    public function __construct(InventoryItem $model)
    {
        parent::__construct($model);
    }

    public function findById(int $id): ?object
    {
        return $this->model->query()->find($id);
    }

    public function getAll(): Collection
    {
        return $this->model->query()->get();
    }

    public function getAllInventoryItems(HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter): mixed
    {
        $query = $this->model->query();

        return $this->applyFilter($query, [
            new FilterInventoryItemByName($filter),
            new FilterInventoryItemBySku($filter),
            new FilterInventoryItemByPriceMin($filter),
            new FilterInventoryItemByPriceMax($filter),
        ])->paginate($filter->getPerPage());
    }

    public function createInventoryItem(HasToCreateArray $data): object
    {
        return $this->model->query()->create($data->toCreateArray());
    }

    public function updateInventoryItem(HasToUpdateArray&HasId $data): ?object
    {
        $item = $this->model->query()->find($data->getId());
        if (!$item) {
            return null;
        }

        $item->update($data->toUpdateArray());

        return $item->fresh();
    }

}
