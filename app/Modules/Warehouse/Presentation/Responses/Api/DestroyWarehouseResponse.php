<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use Dust\Base\Response;

class DestroyWarehouseResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'message' => 'Warehouse deleted successfully.',
        ]);
    }
}
