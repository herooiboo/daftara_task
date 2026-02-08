<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\AddStockToTargetDTO;
use App\Modules\Warehouse\Application\DTOs\CreateStockTransferDTO;
use App\Modules\Warehouse\Application\DTOs\CreateStockTransferResponseDTO;
use App\Modules\Warehouse\Application\DTOs\DeductStockDTO;
use App\Modules\Warehouse\Infrastructure\Repositories\StockTransferRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseInventoryItemRepository;
use Illuminate\Support\Facades\DB;

class CreateStockTransferService
{
    public function __construct(
        protected StockTransferOperationService $stockTransferOperationService,
        protected LowStockCheckService $lowStockCheckService,
        protected StockTransferRepository $stockTransferRepository,
        protected WarehouseInventoryItemRepository $warehouseInventoryItemRepository,
    ) {}

    public function handle(CreateStockTransferDTO $dto): CreateStockTransferResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $sourceStock = $this->warehouseInventoryItemRepository->findByWarehouseAndInventory($dto);

            $this->stockTransferOperationService->deductFromSource(new DeductStockDTO(
                sourceStock: $sourceStock,
                amount: $dto->amount,
                userId: $dto->createdBy,
            ));

            $this->stockTransferOperationService->addToTarget(new AddStockToTargetDTO(
                targetWarehouseId: $dto->targetWarehouseId,
                inventoryId: $dto->inventoryId,
                amount: $dto->amount,
                lowStockThreshold: $sourceStock->low_stock_threshold,
                userId: $dto->createdBy,
            ), $sourceStock->low_stock_threshold);

            $transfer = $this->stockTransferRepository->createStockTransfer($dto);
            $transfer->load(['inventoryItem', 'baseWarehouse', 'targetWarehouse', 'creator']);

            $lowStockEventData = $this->lowStockCheckService->checkLowStock($dto);

            return new CreateStockTransferResponseDTO(
                transfer: $transfer,
                isLowStock: $lowStockEventData !== null,
                lowStockEventData: $lowStockEventData,
            );
        });
    }
}
