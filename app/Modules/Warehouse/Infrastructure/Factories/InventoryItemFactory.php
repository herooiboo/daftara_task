<?php

namespace App\Modules\Warehouse\Infrastructure\Factories;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'SKU' => strtoupper($this->faker->unique()->bothify('??-####')),
            'price' => $this->faker->randomFloat(2, 1, 999),
            'description' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
