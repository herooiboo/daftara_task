<?php

namespace App\Modules\Auth\Presentation\Controllers\Api;

use App\Modules\Auth\Application\Services\LogoutService;
use App\Modules\Auth\Presentation\Requests\Api\LogoutRequest;
use App\Modules\Auth\Presentation\Responses\Api\LogoutResponse;
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
#[Route(Http::POST, 'logout', 'api.v1.auth.logout')]
class LogoutController extends Controller
{
    public function __construct(
        LogoutResponse $response,
        LogoutRequest $request,
        protected LogoutService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        $this->service->handle($request);

        return null;
    }
}
