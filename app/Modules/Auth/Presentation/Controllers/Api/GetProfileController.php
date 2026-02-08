<?php

namespace App\Modules\Auth\Presentation\Controllers\Api;

use App\Modules\Auth\Application\Services\GetProfileService;
use App\Modules\Auth\Presentation\Requests\Api\GetProfileRequest;
use App\Modules\Auth\Presentation\Responses\Api\GetProfileResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Auth
 */
#[Guard('api')]
#[Prefix('v1/auth')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::GET, 'me', 'api.v1.auth.me')]
class GetProfileController extends Controller
{
    public function __construct(
        GetProfileResponse $response,
        GetProfileRequest $request,
        protected GetProfileService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle($request);
    }
}
