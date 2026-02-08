<?php

namespace App\Modules\Audit\Domain\Contracts\Filters;

interface HasEvent
{
    public function getEvent(): ?string;
}
