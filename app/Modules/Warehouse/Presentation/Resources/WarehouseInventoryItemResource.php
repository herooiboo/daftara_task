<?php

namespace App\Modules\Warehouse\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseInventoryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $inventoryItem = $this->relationLoaded('inventoryItem') ? $this->inventoryItem : ($this->inventoryItem ?? null);
        
        return [
            'id' => $this->id,
            'inventory_id' => $this->inventory_id,
            'warehouse_id' => $this->warehouse_id,
            'stock' => $this->stock,
            'low_stock_threshold' => $this->low_stock_threshold,
            'last_updated_by' => $this->last_updated_by,
            // Include inventory item fields directly for easier access
            'name' => $inventoryItem?->name,
            'SKU' => $inventoryItem?->SKU,
            'price' => $inventoryItem?->price,
            'inventory_item' => $this->whenLoaded('inventoryItem', fn () => new InventoryItemResource($this->inventoryItem)),
            'warehouse' => $this->whenLoaded('warehouse', fn () => new WarehouseResource($this->warehouse)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
