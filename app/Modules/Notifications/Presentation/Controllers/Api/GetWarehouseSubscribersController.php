<?php

namespace App\Modules\Notifications\Presentation\Controllers\Api;

use App\Modules\Notifications\Application\Services\GetWarehouseSubscribersService;
use App\Modules\Notifications\Presentation\Requests\Api\GetWarehouseSubscribersRequest;
use App\Modules\Notifications\Presentation\Responses\Api\GetWarehouseSubscribersResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Warehouse Notification Subscriptions
 */
#[Guard('api')]
#[Prefix('v1/notifications')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::GET, 'warehouses/{id}/notification-subscribers', 'api.v1.notifications.warehouses.notification-subscribers.index')]
class GetWarehouseSubscribersController extends Controller
{
    public function __construct(
        GetWarehouseSubscribersResponse $response,
        GetWarehouseSubscribersRequest $request,
        protected GetWarehouseSubscribersService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle($this->request->validated()['id']);
    }
}
