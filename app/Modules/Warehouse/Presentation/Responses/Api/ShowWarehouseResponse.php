<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Warehouse\Presentation\Resources\WarehouseResource;
use Dust\Base\Response;

class ShowWarehouseResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => new WarehouseResource($resource),
        ]);
    }
}
