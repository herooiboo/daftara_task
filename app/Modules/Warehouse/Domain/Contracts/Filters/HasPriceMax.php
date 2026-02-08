<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasPriceMax
{
    public function getPriceMax(): ?float;
}
