<?php

namespace App\Modules\Warehouse\Infrastructure\Repositories;

use App\Modules\Warehouse\Application\Pipelines\FilterByName;
use App\Modules\Warehouse\Application\Pipelines\FilterByPriceMax;
use App\Modules\Warehouse\Application\Pipelines\FilterByPriceMin;
use App\Modules\Warehouse\Application\Pipelines\FilterBySku;
use App\Modules\Warehouse\Application\Pipelines\FilterByWarehouse;
use App\Modules\Warehouse\Application\Pipelines\FilterWarehouseInventoryByName;
use App\Modules\Warehouse\Application\Pipelines\FilterWarehouseInventoryByPriceMax;
use App\Modules\Warehouse\Application\Pipelines\FilterWarehouseInventoryByPriceMin;
use App\Modules\Warehouse\Application\Pipelines\FilterWarehouseInventoryBySku;
use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Domain\Contracts\HasToUpdateArray;
use App\Infrastructure\Repositories\BaseRepository;
use App\Modules\Warehouse\Application\DTOs\CreateWarehouseInventoryItemDTO;
use App\Modules\Warehouse\Application\DTOs\UpdateStockDTO;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasAmount;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseInventoryItemRepositoryInterface;
use App\Modules\Warehouse\Domain\Exceptions\InsufficientStockException;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;

class WarehouseInventoryItemRepository extends BaseRepository implements WarehouseInventoryItemRepositoryInterface
{
    public function __construct(WarehouseInventoryItem $model)
    {
        parent::__construct($model);
    }

    public function getByWarehouseId(int $warehouseId): mixed
    {
        return $this->model->query()
            ->where('warehouse_id', $warehouseId)
            ->with(['inventoryItem', 'lastUpdatedBy'])
            ->get();
    }

    public function findByWarehouseAndInventory(
        HasWarehouseId&HasInventoryId $data
    ): ?object
    {
        return $this->model->query()
            ->where('warehouse_id', $data->getWarehouseId())
            ->where('inventory_id', $data->getInventoryId())
            ->first();
    }

    /**
     * @throws InsufficientStockException
     */
    public function validateStockAvailability(
        HasWarehouseId&HasInventoryId&HasAmount $data
    ): object
    {
        $sourceStock = $this->findByWarehouseAndInventory($data);

        if (!$sourceStock || $sourceStock->stock < $data->getAmount()) {
            throw new InsufficientStockException();
        }

        return $sourceStock;
    }

    public function deductStock(
        HasId&HasAmount&HasUserId $data
    ): object
    {
        $item = $this->model->query()->findOrFail($data->getId());

        return $this->updateStock(new UpdateStockDTO(
            id: $item->id,
            stock: $item->stock - $data->getAmount(),
            userId: $data->getUserId(),
        ));
    }

    public function addStockToTarget(
        HasWarehouseId&HasInventoryId&HasAmount&HasUserId $data,
        float                                             $lowStockThreshold
    ): object
    {
        $targetStock = $this->findByWarehouseAndInventory($data);

        if ($targetStock) {
            return $this->updateStock(new UpdateStockDTO(
                id: $targetStock->id,
                stock: $targetStock->stock + $data->getAmount(),
                userId: $data->getUserId(),
            ));
        }

        return $this->createWarehouseInventoryItem(new CreateWarehouseInventoryItemDTO(
            warehouseId: $data->getWarehouseId(),
            inventoryId: $data->getInventoryId(),
            stock: $data->getAmount(),
            lowStockThreshold: $lowStockThreshold,
            userId: $data->getUserId(),
        ));
    }

    public function createWarehouseInventoryItem(
        HasToCreateArray $data
    ): object
    {
        return $this->model->query()->create($data->toCreateArray());
    }

    public function updateStock(
        HasToUpdateArray&HasId $data
    ): object
    {
        $item = $this->model->query()->findOrFail($data->getId());
        $item->update($data->toUpdateArray());

        return $item->fresh();
    }

    public function getAllInventoryQuery(): mixed
    {
        return $this->model->query()
            ->with(['inventoryItem', 'warehouse', 'lastUpdatedBy']);
    }

    public function getAllInventory(
        HasWarehouseId&HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter
    ): mixed
    {
        $query = $this->getAllInventoryQuery();

        return $this->applyFilter($query, [
            new FilterByWarehouse($filter),
            new FilterByName($filter),
            new FilterBySku($filter),
            new FilterByPriceMin($filter),
            new FilterByPriceMax($filter),
        ])->paginate($filter->getPerPage());
    }

    public function getWarehouseInventory(
        int $warehouseId,
        HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter
    ): mixed
    {
        $query = $this->model->query()
            ->where('warehouse_id', $warehouseId)
            ->with(['inventoryItem', 'lastUpdatedBy']);

        return $this->applyFilter($query, [
            new FilterWarehouseInventoryByName($filter),
            new FilterWarehouseInventoryBySku($filter),
            new FilterWarehouseInventoryByPriceMin($filter),
            new FilterWarehouseInventoryByPriceMax($filter),
        ])->paginate($filter->getPerPage());
    }
}
