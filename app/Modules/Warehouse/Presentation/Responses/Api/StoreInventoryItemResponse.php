<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Warehouse\Presentation\Resources\InventoryItemResource;
use Dust\Base\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class StoreInventoryItemResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => new InventoryItemResource($resource),
        ], SymfonyResponse::HTTP_CREATED);
    }
}
