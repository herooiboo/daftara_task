<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasInventoryId
{
    public function getInventoryId(): ?int;
}
