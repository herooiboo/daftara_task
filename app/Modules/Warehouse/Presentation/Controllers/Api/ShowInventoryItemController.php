<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\Services\ShowInventoryItemService;
use App\Modules\Warehouse\Presentation\Requests\Api\ShowInventoryItemRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\ShowInventoryItemResponse;
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
#[Route(Http::GET, 'inventory-items/{id}', 'api.v1.warehouse.inventory-items.show')]
class ShowInventoryItemController extends Controller
{
    public function __construct(
        ShowInventoryItemResponse $response,
        ShowInventoryItemRequest $request,
        protected ShowInventoryItemService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle($this->request->validated()['id']);
    }
}
