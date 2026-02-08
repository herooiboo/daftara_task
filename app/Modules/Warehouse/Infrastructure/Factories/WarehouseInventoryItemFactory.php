<?php

namespace App\Modules\Warehouse\Infrastructure\Factories;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseInventoryItemFactory extends Factory
{
    protected $model = WarehouseInventoryItem::class;

    public function definition(): array
    {
        return [
            'inventory_id' => InventoryItem::factory(),
            'warehouse_id' => Warehouse::factory(),
            'stock' => $this->faker->randomFloat(2, 0, 500),
            'low_stock_threshold' => $this->faker->randomFloat(2, 5, 50),
            'last_updated_by' => User::factory(),
        ];
    }
}
