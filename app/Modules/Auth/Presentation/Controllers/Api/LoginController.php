<?php

namespace App\Modules\Auth\Presentation\Controllers\Api;

use App\Modules\Auth\Application\Services\LoginService;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Presentation\DTOs\LoginDTO;
use App\Modules\Auth\Presentation\Requests\Api\LoginRequest;
use App\Modules\Auth\Presentation\Responses\Api\LoginResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Auth
 */
#[Guard('api')]
#[Prefix('v1/auth')]
#[Route(Http::POST, 'login', 'api.v1.auth.login')]
class LoginController extends Controller
{
    public function __construct(
        LoginResponse $response,
        LoginRequest $request,
        protected LoginService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @param LoginRequest $request
     * @throws InvalidCredentialsException
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            LoginDTO::fromRequest($request)
        );
    }
}
