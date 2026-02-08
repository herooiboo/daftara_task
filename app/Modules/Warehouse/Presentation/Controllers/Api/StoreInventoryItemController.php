<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\StoreInventoryItemDTO;
use App\Modules\Warehouse\Application\Services\StoreInventoryItemService;
use App\Modules\Warehouse\Presentation\Requests\Api\StoreInventoryItemRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\StoreInventoryItemResponse;
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
#[Route(Http::POST, 'inventory-items', 'api.v1.warehouse.inventory-items.store')]
class StoreInventoryItemController extends Controller
{
    public function __construct(
        StoreInventoryItemResponse $response,
        StoreInventoryItemRequest $request,
        protected StoreInventoryItemService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @param StoreInventoryItemRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            StoreInventoryItemDTO::fromArray($request->validated())
        );
    }
}
