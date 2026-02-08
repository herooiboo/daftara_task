<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasWarehouseId
{
    public function getWarehouseId(): ?int;
}
