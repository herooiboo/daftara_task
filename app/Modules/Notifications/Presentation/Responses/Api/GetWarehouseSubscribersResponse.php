<?php

namespace App\Modules\Notifications\Presentation\Responses\Api;

use App\Modules\Notifications\Presentation\Resources\SubscriptionEntityResource;
use Dust\Base\Response;

class GetWarehouseSubscribersResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => SubscriptionEntityResource::collection($resource),
        ]);
    }
}
