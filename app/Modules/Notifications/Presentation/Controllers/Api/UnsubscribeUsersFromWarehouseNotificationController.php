<?php

namespace App\Modules\Notifications\Presentation\Controllers\Api;

use App\Modules\Notifications\Application\DTOs\UnsubscribeUsersFromWarehouseNotificationDTO;
use App\Modules\Notifications\Application\Services\UnsubscribeUsersFromWarehouseNotificationService;
use App\Modules\Notifications\Presentation\Requests\Api\UnsubscribeUsersFromWarehouseNotificationRequest;
use App\Modules\Notifications\Presentation\Responses\Api\UnsubscribeUsersFromWarehouseNotificationResponse;
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
#[Route(Http::DELETE, 'warehouses/{id}/notification-subscribers', 'api.v1.notifications.warehouses.notification-subscribers.unsubscribe')]
class UnsubscribeUsersFromWarehouseNotificationController extends Controller
{
    public function __construct(
        UnsubscribeUsersFromWarehouseNotificationResponse $response,
        UnsubscribeUsersFromWarehouseNotificationRequest $request,
        protected UnsubscribeUsersFromWarehouseNotificationService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * Unsubscribe multiple users from warehouse low-stock notifications.
     * 
     * @param UnsubscribeUsersFromWarehouseNotificationRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            UnsubscribeUsersFromWarehouseNotificationDTO::fromArray($this->request->validated())
        );
    }
}