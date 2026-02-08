<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasSku
{
    public function getSku(): ?string;
}
