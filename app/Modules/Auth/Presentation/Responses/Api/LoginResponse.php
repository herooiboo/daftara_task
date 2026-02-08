<?php

namespace App\Modules\Auth\Presentation\Responses\Api;

use App\Modules\Auth\Application\DTOs\LoginResponseDTO;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Presentation\Resources\UserResource;
use Dust\Base\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class LoginResponse extends Response
{
    /**
     * @param LoginResponseDTO $resource
     */
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => new UserResource($resource->getUser()),
                'token' => $resource->getToken(),
            ],
        ], SymfonyResponse::HTTP_OK);
    }

    protected function handleErrorResponse(Throwable $e): false|JsonResponse
    {
        if ($e instanceof InvalidCredentialsException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], SymfonyResponse::HTTP_UNAUTHORIZED);
        }

        return false;
    }
}
