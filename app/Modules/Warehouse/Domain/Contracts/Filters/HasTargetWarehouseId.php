<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasTargetWarehouseId
{
    public function getTargetWarehouseId(): ?int;
}
