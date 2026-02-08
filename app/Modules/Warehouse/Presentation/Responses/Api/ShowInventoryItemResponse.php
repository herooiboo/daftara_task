<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Warehouse\Presentation\Resources\InventoryItemResource;
use Dust\Base\Response;

class ShowInventoryItemResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => new InventoryItemResource($resource),
        ]);
    }
}
