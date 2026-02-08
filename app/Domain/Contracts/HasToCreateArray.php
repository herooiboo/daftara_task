<?php

namespace App\Domain\Contracts;

interface HasToCreateArray
{
    public function toCreateArray(): array;
}
