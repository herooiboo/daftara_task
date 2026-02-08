<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\Services\ShowWarehouseService;
use App\Modules\Warehouse\Presentation\Requests\Api\ShowWarehouseRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\ShowWarehouseResponse;
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
#[Route(Http::GET, 'warehouses/{id}', 'api.v1.warehouse.warehouses.show')]
class ShowWarehouseController extends Controller
{
    public function __construct(
        ShowWarehouseResponse $response,
        ShowWarehouseRequest $request,
        protected ShowWarehouseService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle($this->request->validated()['id']);
    }
}
