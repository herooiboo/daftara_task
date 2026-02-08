<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\Services\DestroyWarehouseService;
use App\Modules\Warehouse\Domain\Exceptions\WarehouseNotFoundException;
use App\Modules\Warehouse\Presentation\Requests\Api\DestroyWarehouseRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\DestroyWarehouseResponse;
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
#[Route(Http::DELETE, 'warehouses/{id}', 'api.v1.warehouse.warehouses.destroy')]
class DestroyWarehouseController extends Controller
{
    public function __construct(
        DestroyWarehouseResponse $response,
        DestroyWarehouseRequest $request,
        protected DestroyWarehouseService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @throws WarehouseNotFoundException
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle($this->request->validated()['id']);
    }
}
