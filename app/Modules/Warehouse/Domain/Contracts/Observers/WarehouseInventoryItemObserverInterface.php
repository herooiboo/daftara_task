<?php

namespace App\Modules\Warehouse\Domain\Contracts\Observers;

use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;

interface WarehouseInventoryItemObserverInterface
{
    public function updated(WarehouseInventoryItem $item): void;

    public function created(WarehouseInventoryItem $item): void;

    public function deleted(WarehouseInventoryItem $item): void;
}
