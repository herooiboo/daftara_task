<?php

namespace App\Modules\Warehouse\Infrastructure\Seeders;

use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseInventoryItemRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Database\Seeder;

class WarehouseInventoryItemsSeeder extends Seeder
{
    public function __construct(
        protected UserRepository $userRepository,
        protected WarehouseRepository $warehouseRepository,
        protected InventoryItemRepository $inventoryItemRepository,
        protected WarehouseInventoryItemRepository $warehouseInventoryItemRepository,
    ) {}

    public function run(): void
    {
        $admin = $this->userRepository->findByEmail('superadmin@daftara.com');
        $warehouses = $this->warehouseRepository->getAll();
        $items = $this->inventoryItemRepository->getAll();

        foreach ($warehouses as $warehouse) {
            foreach ($items as $item) {
                $this->warehouseInventoryItemRepository->firstOrCreate(
                    [
                        'inventory_id' => $item->id,
                        'warehouse_id' => $warehouse->id,
                    ],
                    [
                        'stock' => round(rand(10, 200) + (rand(0, 99) / 100), 2),
                        'low_stock_threshold' => round(rand(5, 20) + (rand(0, 99) / 100), 2),
                        'last_updated_by' => $admin->id,
                    ]
                );
            }
        }
    }
}
