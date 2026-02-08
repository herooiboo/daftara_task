<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasStock
{
    public function getStock(): float;
}
