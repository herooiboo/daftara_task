<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\IndexInventoryItemDTO;
use App\Modules\Warehouse\Application\Services\IndexInventoryItemService;
use App\Modules\Warehouse\Presentation\Requests\Api\IndexInventoryItemRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\IndexInventoryItemResponse;
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
#[Route(Http::GET, 'inventory-items', 'api.v1.warehouse.inventory-items.index')]
class IndexInventoryItemController extends Controller
{
    public function __construct(
        IndexInventoryItemResponse $response,
        IndexInventoryItemRequest $request,
        protected IndexInventoryItemService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            IndexInventoryItemDTO::fromArray($this->request->validated())
        );
    }
}
