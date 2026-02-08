<?php

namespace App\Modules\Warehouse\Infrastructure\Seeders;

use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use App\Modules\Warehouse\Infrastructure\Repositories\InventoryItemRepository;
use Illuminate\Database\Seeder;

class InventoryItemsSeeder extends Seeder
{
    public function __construct(
        protected UserRepository $userRepository,
        protected InventoryItemRepository $inventoryItemRepository,
    ) {}

    public function run(): void
    {
        $admin = $this->userRepository->findByEmail('superadmin@daftara.com');

        $items = [
            ['name' => 'Laptop', 'SKU' => 'LP-0001', 'price' => 999.99, 'description' => 'High-performance laptop'],
            ['name' => 'Mouse', 'SKU' => 'MS-0001', 'price' => 29.99, 'description' => 'Wireless optical mouse'],
            ['name' => 'Keyboard', 'SKU' => 'KB-0001', 'price' => 49.99, 'description' => 'Mechanical keyboard'],
            ['name' => 'Monitor', 'SKU' => 'MN-0001', 'price' => 299.99, 'description' => '27-inch 4K monitor'],
            ['name' => 'Headset', 'SKU' => 'HS-0001', 'price' => 79.99, 'description' => 'Noise-canceling headset'],
            ['name' => 'USB Cable', 'SKU' => 'UC-0001', 'price' => 9.99, 'description' => 'USB-C to USB-A cable'],
            ['name' => 'Webcam', 'SKU' => 'WC-0001', 'price' => 59.99, 'description' => 'HD webcam'],
            ['name' => 'Desk Lamp', 'SKU' => 'DL-0001', 'price' => 34.99, 'description' => 'LED desk lamp'],
            ['name' => 'Mousepad', 'SKU' => 'MP-0001', 'price' => 14.99, 'description' => 'Large gaming mousepad'],
            ['name' => 'Charger', 'SKU' => 'CH-0001', 'price' => 24.99, 'description' => '65W USB-C charger'],
        ];

        foreach ($items as $item) {
            $this->inventoryItemRepository->firstOrCreate(
                ['SKU' => $item['SKU']],
                array_merge($item, ['created_by' => $admin->id])
            );
        }
    }
}
