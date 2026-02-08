<?php

namespace App\Modules\Warehouse\Domain\Contracts\Observers;

interface WarehouseInventoryItemObserverInterface
{
    public function updated(object $item): void;

    public function created(object $item): void;

    public function deleted(object $item): void;
}
