<?php

namespace App\Modules\Audit\Presentation\Controllers\Api;

use App\Modules\Audit\Application\DTOs\GetActivityLogsDTO;
use App\Modules\Audit\Application\Services\GetActivityLogsService;
use App\Modules\Audit\Presentation\Requests\Api\GetActivityLogsRequest;
use App\Modules\Audit\Presentation\Responses\Api\GetActivityLogsResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Audit
 */
#[Guard('api')]
#[Prefix('v1/audit')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::GET, 'activity-logs', 'api.v1.audit.activity-logs.index')]
class GetActivityLogsController extends Controller
{
    public function __construct(
        GetActivityLogsResponse $response,
        GetActivityLogsRequest $request,
        protected GetActivityLogsService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            GetActivityLogsDTO::fromArray($this->request->validated())
        );
    }
}
