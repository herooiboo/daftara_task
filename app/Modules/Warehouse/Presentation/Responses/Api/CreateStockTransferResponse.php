<?php

namespace App\Modules\Warehouse\Presentation\Responses\Api;

use App\Modules\Notifications\Infrastructure\Events\LowStockDetected;
use App\Modules\Warehouse\Application\DTOs\CreateStockTransferResponseDTO;
use App\Modules\Warehouse\Domain\Exceptions\InsufficientStockException;
use App\Modules\Warehouse\Presentation\Resources\StockTransferResource;
use Dust\Base\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class CreateStockTransferResponse extends Response
{
    /**
     * @param CreateStockTransferResponseDTO $resource
     */
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => new StockTransferResource($resource->transfer),
        ], SymfonyResponse::HTTP_CREATED);
    }

    /**
     * @param CreateStockTransferResponseDTO $resource
     */
    protected function success(mixed $resource): void
    {
        if ($resource->isLowStock && $resource->lowStockEventData) {
            event(new LowStockDetected($resource->lowStockEventData));
        }
    }

    protected function handleErrorResponse(Throwable $e): false|JsonResponse
    {
        if ($e instanceof InsufficientStockException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => ['amount' => 'Insufficient stock in source warehouse.'],
            ], SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return false;
    }
}
