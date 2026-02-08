<?php

namespace App\Modules\Audit\Domain\Contracts\Filters;

interface HasDateTo
{
    public function getDateTo(): ?string;
}
