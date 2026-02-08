<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasAmount
{
    public function getAmount(): float;
}
