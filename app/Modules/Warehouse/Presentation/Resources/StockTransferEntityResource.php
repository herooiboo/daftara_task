<?php

namespace App\Modules\Warehouse\Presentation\Resources;

use App\Modules\Warehouse\Domain\Entities\StockTransfer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for StockTransfer domain entities.
 * Use StockTransferResource for Eloquent models.
 */
class StockTransferEntityResource extends JsonResource
{
    /**
     * @param StockTransfer $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        /** @var StockTransfer $transfer */
        $transfer = $this->resource;

        return [
            'id' => $transfer->id,
            'inventory_id' => $transfer->inventoryId,
            'base_warehouse_id' => $transfer->sourceWarehouseId,
            'target_warehouse_id' => $transfer->destinationWarehouseId,
            'amount' => $transfer->quantity,
            'created_by' => $transfer->performedBy,
            'created_at' => $transfer->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
