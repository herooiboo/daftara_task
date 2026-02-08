<?php

namespace App\Modules\Audit\Domain\Contracts\Filters;

interface HasSubjectType
{
    public function getSubjectType(): ?string;
}
