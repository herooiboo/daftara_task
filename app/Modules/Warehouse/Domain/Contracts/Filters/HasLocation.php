<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasLocation
{
    public function getLocation(): ?string;
}
