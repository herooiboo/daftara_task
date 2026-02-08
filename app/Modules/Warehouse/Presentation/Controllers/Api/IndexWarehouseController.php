<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\IndexWarehouseDTO;
use App\Modules\Warehouse\Application\Services\IndexWarehouseService;
use App\Modules\Warehouse\Presentation\Requests\Api\IndexWarehouseRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\IndexWarehouseResponse;
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
#[Route(Http::GET, 'warehouses', 'api.v1.warehouse.warehouses.index')]
class IndexWarehouseController extends Controller
{
    public function __construct(
        IndexWarehouseResponse $response,
        IndexWarehouseRequest $request,
        protected IndexWarehouseService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            IndexWarehouseDTO::fromArray($this->request->validated())
        );
    }
}
