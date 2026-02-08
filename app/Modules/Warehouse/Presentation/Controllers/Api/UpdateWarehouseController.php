<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\UpdateWarehouseDTO;
use App\Modules\Warehouse\Application\Services\UpdateWarehouseService;
use App\Modules\Warehouse\Presentation\Requests\Api\UpdateWarehouseRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\UpdateWarehouseResponse;
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
#[Route(Http::PUT, 'warehouses/{id}', 'api.v1.warehouse.warehouses.update')]
class UpdateWarehouseController extends Controller
{
    public function __construct(
        UpdateWarehouseResponse $response,
        UpdateWarehouseRequest $request,
        protected UpdateWarehouseService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @param UpdateWarehouseRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        $data = $request->validated();
        $data['id'] = (int) $request->route('id');

        return $this->service->handle(
            UpdateWarehouseDTO::fromArray($data)
        );
    }
}
