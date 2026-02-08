<?php

namespace App\Modules\Audit\Domain\Contracts\Filters;

interface HasDateFrom
{
    public function getDateFrom(): ?string;
}
