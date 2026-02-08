<?php

namespace App\Modules\Audit\Presentation\Responses\Api;

use App\Modules\Audit\Presentation\Resources\ActivityLogResource;
use Dust\Base\Response;

class GetActivityLogsResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => ActivityLogResource::collection($resource),
        ]);
    }
}
