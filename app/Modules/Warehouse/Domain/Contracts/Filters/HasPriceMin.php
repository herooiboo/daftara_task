<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasPriceMin
{
    public function getPriceMin(): ?float;
}
