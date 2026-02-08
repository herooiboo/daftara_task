<?php

namespace App\Modules\Warehouse\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for StockTransfer Eloquent models.
 * Use StockTransferEntityResource for domain entities.
 */
class StockTransferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inventory_id' => $this->inventory_id,
            'base_warehouse_id' => $this->base_warehouse_id,
            'target_warehouse_id' => $this->target_warehouse_id,
            'amount' => $this->amount,
            'created_by' => $this->created_by,
            'inventory_item' => new InventoryItemResource($this->whenLoaded('inventoryItem')),
            'base_warehouse' => new WarehouseResource($this->whenLoaded('baseWarehouse')),
            'target_warehouse' => new WarehouseResource($this->whenLoaded('targetWarehouse')),
            'creator' => $this->whenLoaded('creator', fn () => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
