<?php

namespace App\Modules\Notifications\Domain\Contracts\Filters;

interface HasUserIds
{
    public function getUserIds(): array;
}