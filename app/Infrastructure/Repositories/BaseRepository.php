<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\FilterInterface;
use Dust\Base\Repository as DustRepository;
use Illuminate\Pipeline\Pipeline;

abstract class BaseRepository extends DustRepository
{
    protected function applyFilter($query, array $filters)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($filters)
            ->thenReturn();
    }
}
