<?php

namespace App\Modules\Auth\Presentation\Responses\Api;

use App\Modules\Auth\Presentation\Resources\UserResource;
use Dust\Base\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class GetProfileResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($resource),
        ], SymfonyResponse::HTTP_OK);
    }
}
