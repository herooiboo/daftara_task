<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\GetAllInventoryDTO;
use App\Modules\Warehouse\Application\Services\GetAllInventoryService;
use App\Modules\Warehouse\Presentation\Requests\Api\GetAllInventoryRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\GetAllInventoryResponse;
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
#[Route(Http::GET, 'inventory', 'api.v1.warehouse.inventory.index')]
class GetAllInventoryController extends Controller
{
    public function __construct(
        GetAllInventoryResponse $response,
        GetAllInventoryRequest $request,
        protected GetAllInventoryService $service,
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            GetAllInventoryDTO::fromArray($this->request->validated())
        );
    }
}
