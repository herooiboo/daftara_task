<?php

namespace App\Modules\Warehouse\Application\Services;

use App\Modules\Warehouse\Application\DTOs\AddStockToTargetDTO;
use App\Modules\Warehouse\Application\DTOs\CreateStockTransferDTO;
use App\Modules\Warehouse\Application\DTOs\CreateStockTransferResponseDTO;
use App\Modules\Warehouse\Application\DTOs\DeductStockDTO;
use App\Modules\Warehouse\Domain\Contracts\Repositories\StockTransferRepositoryInterface;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseInventoryItemRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CreateStockTransferService
{
    public function __construct(
        protected StockTransferOperationService $stockTransferOperationService,
        protected LowStockCheckService $lowStockCheckService,
        protected StockTransferRepositoryInterface $stockTransferRepository,
        protected WarehouseInventoryItemRepositoryInterface $warehouseInventoryItemRepository,
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

            /** @var \App\Modules\Warehouse\Infrastructure\Models\StockTransfer $transferModel */
            $transferModel = $this->stockTransferRepository->createStockTransfer($dto);
            $transferModel->load(['inventoryItem', 'baseWarehouse', 'targetWarehouse', 'creator']);

            // Convert to domain entity
            $transfer = \App\Modules\Warehouse\Domain\Entities\StockTransfer::fromArray([
                'id' => $transferModel->id,
                'inventory_id' => $transferModel->inventory_id,
                'source_warehouse_id' => $transferModel->base_warehouse_id,
                'destination_warehouse_id' => $transferModel->target_warehouse_id,
                'quantity' => (float) $transferModel->amount,
                'performed_by' => $transferModel->created_by,
                'created_at' => $transferModel->created_at,
            ]);

            $lowStockEventData = $this->lowStockCheckService->checkLowStock($dto);

            return new CreateStockTransferResponseDTO(
                transfer: $transfer,
                isLowStock: $lowStockEventData !== null,
                lowStockEventData: $lowStockEventData,
            );
        });
    }
}
