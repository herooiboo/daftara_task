<?php

namespace App\Domain\Contracts;

use Closure;

interface FilterInterface
{
    public function handle($query, Closure $next);
}
