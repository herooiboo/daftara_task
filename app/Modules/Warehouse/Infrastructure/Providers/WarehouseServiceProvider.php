<?php

namespace App\Modules\Warehouse\Infrastructure\Providers;

use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;
use App\Modules\Warehouse\Domain\Contracts\Repositories\StockTransferRepositoryInterface;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseInventoryItemRepositoryInterface;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use App\Modules\Warehouse\Infrastructure\Observers\WarehouseInventoryItemObserver;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\StockTransferRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseInventoryItemRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WarehouseRepositoryInterface::class, WarehouseRepository::class);
        $this->app->bind(InventoryItemRepositoryInterface::class, InventoryItemRepository::class);
        $this->app->bind(WarehouseInventoryItemRepositoryInterface::class, WarehouseInventoryItemRepository::class);
        $this->app->bind(StockTransferRepositoryInterface::class, StockTransferRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        WarehouseInventoryItem::observe(WarehouseInventoryItemObserver::class);
    }
}
