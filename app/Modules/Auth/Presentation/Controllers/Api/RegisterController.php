<?php

namespace App\Modules\Auth\Presentation\Controllers\Api;

use App\Modules\Auth\Application\Services\RegisterService;
use App\Modules\Auth\Domain\Exceptions\UserAlreadyExistsException;
use App\Modules\Auth\Presentation\DTOs\RegisterDTO;
use App\Modules\Auth\Presentation\Requests\Api\RegisterRequest;
use App\Modules\Auth\Presentation\Responses\Api\RegisterResponse;
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
#[Route(Http::POST, 'register', 'api.v1.auth.register')]
class RegisterController extends Controller
{
    public function __construct(
        RegisterResponse $response,
        RegisterRequest $request,
        protected RegisterService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @param RegisterRequest $request
     * @throws UserAlreadyExistsException
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            RegisterDTO::fromRequest($request)
        );
    }
}
