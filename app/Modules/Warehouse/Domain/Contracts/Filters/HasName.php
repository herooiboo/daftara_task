<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasName
{
    public function getName(): ?string;
}
