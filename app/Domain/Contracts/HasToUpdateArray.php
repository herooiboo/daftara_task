<?php

namespace App\Domain\Contracts;

interface HasToUpdateArray
{
    public function toUpdateArray(): array;
}
