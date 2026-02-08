<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Warehouse\Presentation\Resources\InventoryItemResource;
use Dust\Base\Response;

class IndexInventoryItemResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => InventoryItemResource::collection($resource),
        ]);
    }
}
