<?php

namespace App\Domain\Contracts;

interface HasPagination
{
    public function getPerPage(): int;

    public function getPage(): int;
}
