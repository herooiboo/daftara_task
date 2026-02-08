<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\UpdateInventoryItemDTO;
use App\Modules\Warehouse\Application\Services\UpdateInventoryItemService;
use App\Modules\Warehouse\Presentation\Requests\Api\UpdateInventoryItemRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\UpdateInventoryItemResponse;
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
#[Route(Http::PUT, 'inventory-items/{id}', 'api.v1.warehouse.inventory-items.update')]
class UpdateInventoryItemController extends Controller
{
    public function __construct(
        UpdateInventoryItemResponse $response,
        UpdateInventoryItemRequest $request,
        protected UpdateInventoryItemService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @param UpdateInventoryItemRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        $data = $request->validated();
        $data['id'] = (int) $request->route('id');

        return $this->service->handle(
            UpdateInventoryItemDTO::fromArray($data)
        );
    }
}
