<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasDescription
{
    public function getDescription(): ?string;
}
