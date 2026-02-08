<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use Dust\Base\Response;

class DestroyInventoryItemResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'message' => 'Inventory item deleted successfully.',
        ]);
    }
}
