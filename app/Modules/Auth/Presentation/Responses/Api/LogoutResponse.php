<?php

namespace App\Modules\Auth\Presentation\Responses\Api;

use Dust\Base\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class LogoutResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], SymfonyResponse::HTTP_OK);
    }
}
