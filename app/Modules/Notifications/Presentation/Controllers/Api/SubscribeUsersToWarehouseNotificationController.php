<?php

namespace App\Modules\Notifications\Presentation\Controllers\Api;

use App\Modules\Notifications\Application\DTOs\SubscribeUsersToWarehouseNotificationDTO;
use App\Modules\Notifications\Application\Services\SubscribeUsersToWarehouseNotificationService;
use App\Modules\Notifications\Presentation\Requests\Api\SubscribeUsersToWarehouseNotificationRequest;
use App\Modules\Notifications\Presentation\Responses\Api\SubscribeUsersToWarehouseNotificationResponse;
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
#[Route(Http::POST, 'warehouses/{id}/notification-subscribers', 'api.v1.notifications.warehouses.notification-subscribers.subscribe')]
class SubscribeUsersToWarehouseNotificationController extends Controller
{
    public function __construct(
        SubscribeUsersToWarehouseNotificationResponse $response,
        SubscribeUsersToWarehouseNotificationRequest $request,
        protected SubscribeUsersToWarehouseNotificationService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * Subscribe multiple users to warehouse low-stock notifications.
     * 
     * @param SubscribeUsersToWarehouseNotificationRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            SubscribeUsersToWarehouseNotificationDTO::fromArray($this->request->validated())
        );
    }
}