<?php

namespace App\Modules\Audit\Domain\Contracts\Filters;

interface HasCauserId
{
    public function getCauserId(): ?int;
}
