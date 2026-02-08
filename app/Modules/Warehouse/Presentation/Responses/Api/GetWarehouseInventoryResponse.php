<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Warehouse\Presentation\Resources\WarehouseInventoryItemResource;
use Dust\Base\Response;

class GetWarehouseInventoryResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return $resource;
    }
}
