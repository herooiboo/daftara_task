<?php

namespace App\Modules\Warehouse\Infrastructure\Factories;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\StockTransfer;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockTransferFactory extends Factory
{
    protected $model = StockTransfer::class;

    public function definition(): array
    {
        return [
            'inventory_id' => InventoryItem::factory(),
            'base_warehouse_id' => Warehouse::factory(),
            'target_warehouse_id' => Warehouse::factory(),
            'amount' => $this->faker->randomFloat(2, 0.01, 100),
            'created_by' => User::factory(),
        ];
    }
}
