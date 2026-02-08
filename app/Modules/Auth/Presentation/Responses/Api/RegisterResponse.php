<?php

namespace App\Modules\Auth\Presentation\Responses\Api;

use App\Modules\Auth\Presentation\Resources\UserResource;
use Dust\Base\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RegisterResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => new UserResource($resource),
        ], SymfonyResponse::HTTP_CREATED);
    }
}
