<?php

namespace App\Modules\Warehouse\Domain\Contracts\Filters;

interface HasUserId
{
    public function getUserId(): int;
}
