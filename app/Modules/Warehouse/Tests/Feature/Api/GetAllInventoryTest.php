<?php

namespace App\Modules\Warehouse\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * @group warehouse
 */
class GetAllInventoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    protected function createUserWithRole(string $roleName): User
    {
        $user = User::factory()->create();
        $role = Role::findByName($roleName, 'api');
        $user->assignRole($role);
        $user->refresh();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return $user;
    }

    public function test_user_can_get_all_inventory_with_filters(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item1 = InventoryItem::factory()->create(['name' => 'Item A', 'SKU' => 'SKU-001', 'price' => 50.00]);
        $item2 = InventoryItem::factory()->create(['name' => 'Item B', 'SKU' => 'SKU-002', 'price' => 150.00]);

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item1->id,
            'stock' => 100,
        ]);

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item2->id,
            'stock' => 50,
        ]);

        $response = $this->getJson('/api/v1/warehouse/inventory?warehouse_id=' . $warehouse->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'SKU', 'price', 'stock'],
                ],
            ]);
    }

    public function test_user_can_filter_inventory_by_name(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item1 = InventoryItem::factory()->create(['name' => 'Apple Product']);
        $item2 = InventoryItem::factory()->create(['name' => 'Banana Product']);

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item1->id,
        ]);

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item2->id,
        ]);

        $response = $this->getJson('/api/v1/warehouse/inventory?name=Apple');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Apple Product');
    }

    public function test_user_can_filter_inventory_by_sku(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create(['SKU' => 'UNIQUE-SKU-123']);

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        $response = $this->getJson('/api/v1/warehouse/inventory?sku=UNIQUE-SKU-123');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.SKU', 'UNIQUE-SKU-123');
    }

    public function test_user_can_filter_inventory_by_price_range(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item1 = InventoryItem::factory()->create(['price' => 50.00]);
        $item2 = InventoryItem::factory()->create(['price' => 150.00]);
        $item3 = InventoryItem::factory()->create(['price' => 250.00]);

        WarehouseInventoryItem::factory()->create(['warehouse_id' => $warehouse->id, 'inventory_id' => $item1->id]);
        WarehouseInventoryItem::factory()->create(['warehouse_id' => $warehouse->id, 'inventory_id' => $item2->id]);
        WarehouseInventoryItem::factory()->create(['warehouse_id' => $warehouse->id, 'inventory_id' => $item3->id]);

        $response = $this->getJson('/api/v1/warehouse/inventory?price_min=100&price_max=200');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals(150.00, $data[0]['price']);
    }
}
