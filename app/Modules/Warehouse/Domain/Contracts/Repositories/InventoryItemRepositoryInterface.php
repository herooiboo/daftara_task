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
use Illuminate\Database\Eloquent\Model;

interface InventoryItemRepositoryInterface
{
    public function findById(int $id): ?Model;

    public function getAll(): mixed;

    public function getAllInventoryItems(HasName&HasSku&HasPriceMin&HasPriceMax&HasPagination $filter): mixed;

    public function createInventoryItem(HasToCreateArray $data): Model;

    public function updateInventoryItem(HasToUpdateArray&HasId $data): ?Model;

    public function delete(int $id): bool;
}
