<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\StoreWarehouseDTO;
use App\Modules\Warehouse\Application\Services\StoreWarehouseService;
use App\Modules\Warehouse\Presentation\Requests\Api\StoreWarehouseRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\StoreWarehouseResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Warehouses
 */
#[Guard('api')]
#[Prefix('v1/warehouse')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::POST, 'warehouses', 'api.v1.warehouse.warehouses.store')]
class StoreWarehouseController extends Controller
{
    public function __construct(
        StoreWarehouseResponse $response,
        StoreWarehouseRequest $request,
        protected StoreWarehouseService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @param StoreWarehouseRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            StoreWarehouseDTO::fromArray($request->validated())
        );
    }
}
