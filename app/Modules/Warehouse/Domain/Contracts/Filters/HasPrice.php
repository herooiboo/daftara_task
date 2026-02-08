<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasPrice
{
    public function getPrice(): ?float;
}
