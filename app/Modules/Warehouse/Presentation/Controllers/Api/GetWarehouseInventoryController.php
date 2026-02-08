<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\GetWarehouseInventoryDTO;
use App\Modules\Warehouse\Application\Services\GetWarehouseInventoryService;
use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;
use App\Modules\Warehouse\Presentation\Requests\Api\GetWarehouseInventoryRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\GetWarehouseInventoryResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Inventory
 */
#[Guard('api')]
#[Prefix('v1/warehouse')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::GET, 'warehouses/{id}/inventory', 'api.v1.warehouse.warehouses.inventory')]
class GetWarehouseInventoryController extends Controller
{
    public function __construct(
        GetWarehouseInventoryResponse $response,
        GetWarehouseInventoryRequest $request,
        protected GetWarehouseInventoryService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            (int) $request->route('id'),
            GetWarehouseInventoryDTO::fromArray($this->request->validated())
        );
    }
}
