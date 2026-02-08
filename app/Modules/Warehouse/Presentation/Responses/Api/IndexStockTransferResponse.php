<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Warehouse\Presentation\Resources\StockTransferResource;
use Dust\Base\Response;

class IndexStockTransferResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => StockTransferResource::collection($resource->items()),
            'pagination' => [
                'current_page' => $resource->currentPage(),
                'per_page' => $resource->perPage(),
                'total' => $resource->total(),
                'last_page' => $resource->lastPage(),
            ],
        ]);
    }
}
