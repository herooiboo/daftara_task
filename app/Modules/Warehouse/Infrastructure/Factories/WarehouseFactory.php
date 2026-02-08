<?php

namespace App\Modules\Warehouse\Infrastructure\Factories;

use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'location' => $this->faker->city(),
        ];
    }
}
