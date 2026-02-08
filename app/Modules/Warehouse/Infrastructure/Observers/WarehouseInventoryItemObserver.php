<?php

namespace App\Modules\Warehouse\Infrastructure\Observers;

use App\Modules\Warehouse\Domain\Contracts\Observers\WarehouseInventoryItemObserverInterface;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Illuminate\Support\Facades\Cache;

class WarehouseInventoryItemObserver implements WarehouseInventoryItemObserverInterface
{
    public function created(object $item): void
    {
        $this->invalidateCache($item);
    }

    public function updated(object $item): void
    {
        $this->invalidateCache($item);
    }

    public function deleted(object $item): void
    {
        $this->invalidateCache($item);
    }

    private function invalidateCache(WarehouseInventoryItem $item): void
    {
        Cache::forget("warehouse_{$item->warehouse_id}_inventory");
    }
}
