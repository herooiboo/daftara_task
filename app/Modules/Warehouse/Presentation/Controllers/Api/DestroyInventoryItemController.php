<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\Services\DestroyInventoryItemService;
use App\Modules\Warehouse\Presentation\Requests\Api\DestroyInventoryItemRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\DestroyInventoryItemResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Inventory Items
 */
#[Guard('api')]
#[Prefix('v1/warehouse')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::DELETE, 'inventory-items/{id}', 'api.v1.warehouse.inventory-items.destroy')]
class DestroyInventoryItemController extends Controller
{
    public function __construct(
        DestroyInventoryItemResponse $response,
        DestroyInventoryItemRequest $request,
        protected DestroyInventoryItemService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle($this->request->validated()['id']);
    }
}
