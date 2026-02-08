<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasBaseWarehouseId
{
    public function getBaseWarehouseId(): ?int;
}
