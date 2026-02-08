<?php

namespace App\Modules\Notifications\Infrastructure\Seeders;

use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use App\Modules\Notifications\Infrastructure\Repositories\WarehouseNotificationSubscriptionRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Database\Seeder;

class WarehouseNotificationSubscriptionsSeeder extends Seeder
{
    public function __construct(
        protected UserRepository $userRepository,
        protected WarehouseRepository $warehouseRepository,
        protected WarehouseNotificationSubscriptionRepository $warehouseNotificationSubscriptionRepository,
    ) {}

    public function run(): void
    {
        $admin = $this->userRepository->findByEmail('superadmin@daftara.com');
        $warehouses = $this->warehouseRepository->getAll();

        foreach ($warehouses as $warehouse) {
            $this->warehouseNotificationSubscriptionRepository->firstOrCreate([
                'user_id' => $admin->id,
                'warehouse_id' => $warehouse->id,
            ]);
        }
    }
}
