<?php

namespace App\Modules\Warehouse\Domain\Contracts\Repositories;

use App\Domain\Contracts\HasPagination;
use App\Domain\Contracts\HasToCreateArray;
use App\Domain\Contracts\HasToUpdateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;

interface InventoryItemRepositoryInterface
{
    public function findById(int $id): ?object;

    public function getAll(): mixed;

    public function getAllInventoryItems(HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter): mixed;

    public function createInventoryItem(HasToCreateArray $data): object;

    public function updateInventoryItem(HasToUpdateArray&HasId $data): ?object;

    public function delete(int $id): bool;
}
