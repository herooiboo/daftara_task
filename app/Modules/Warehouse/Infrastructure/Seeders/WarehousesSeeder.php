<?php

namespace App\Modules\Warehouse\Infrastructure\Seeders;

use App\Modules\Warehouse\Infrastructure\Repositories\WarehouseRepository;
use Illuminate\Database\Seeder;

class WarehousesSeeder extends Seeder
{
    public function __construct(
        protected WarehouseRepository $warehouseRepository,
    ) {}

    public function run(): void
    {
        $warehouses = [
            ['name' => 'Main Warehouse', 'location' => 'Cairo'],
            ['name' => 'North Branch', 'location' => 'Alexandria'],
            ['name' => 'South Branch', 'location' => 'Aswan'],
        ];

        foreach ($warehouses as $warehouse) {
            $this->warehouseRepository->firstOrCreate(['name' => $warehouse['name']], $warehouse);
        }
    }
}
