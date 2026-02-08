<?php

namespace App\Modules\Warehouse\Presentation\Controllers\Api;

use App\Modules\Warehouse\Application\DTOs\IndexStockTransferDTO;
use App\Modules\Warehouse\Application\Services\IndexStockTransferService;
use App\Modules\Warehouse\Presentation\Requests\Api\IndexStockTransferRequest;
use App\Modules\Warehouse\Presentation\Responses\Api\IndexStockTransferResponse;
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
#[Route(Http::GET, 'stock-transfers', 'api.v1.warehouse.stock-transfers.index')]
class IndexStockTransferController extends Controller
{
    public function __construct(
        IndexStockTransferResponse $response,
        IndexStockTransferRequest $request,
        protected IndexStockTransferService $service,
    ) {
        parent::__construct($response, $request);
    }

    /**
     * List all stock transfers with optional filters.
     * 
     * @param IndexStockTransferRequest $request
     */
    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->handle(
            IndexStockTransferDTO::fromArray($this->request->validated())
        );
    }
}
