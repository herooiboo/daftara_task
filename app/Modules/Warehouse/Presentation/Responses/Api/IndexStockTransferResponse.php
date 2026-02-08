<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Warehouse\Presentation\Resources\StockTransferResource;
use Dust\Base\Response;

class IndexStockTransferResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return $resource;
    }
}
