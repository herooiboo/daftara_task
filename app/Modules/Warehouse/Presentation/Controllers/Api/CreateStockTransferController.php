<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\CreateStockTransferDTO;
use App\Modules\Warehouse\Application\Services\CreateStockTransferService;
use App\Modules\Warehouse\Presentation\Requests\Api\CreateStockTransferRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\CreateStockTransferResponse;
use Dust\Base\Contracts\ResponseInterface;
use Dust\Base\Controller;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Route;
use Dust\Http\Router\Enum\Http;
use Illuminate\Http\Request;

/**
 * @tags Stock Transfers
 */
#[Guard('api')]
#[Prefix('v1/warehouse')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::POST, 'stock-transfers', 'api.v1.warehouse.stock-transfers.store')]
class CreateStockTransferController extends Controller
{
    public function __construct(
        CreateStockTransferResponse $response,
        CreateStockTransferRequest $request,
        protected CreateStockTransferService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * @param CreateStockTransferRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            CreateStockTransferDTO::fromArray($request->validated())
        );
    }
}
