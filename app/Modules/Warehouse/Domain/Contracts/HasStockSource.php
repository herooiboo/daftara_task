<?php

namespace App\Modules\Warehouse\Domain\Contracts;

/**
 * Interface for objects that represent a stock source in the warehouse.
 * Used to abstract away from Eloquent models in Application layer DTOs.
 */
interface HasStockSource
{
    public function getId(): int;

    public function getWarehouseId(): int;

    public function getInventoryId(): int;
}
