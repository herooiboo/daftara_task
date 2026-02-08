<?php

namespace App\Modules\Warehouse\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * @group warehouse
 */
class GetWarehouseInventoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    protected function createUserWithRole(string $roleName): User
    {
        $user = User::factory()->create();
        $role = Role::findByName($roleName, 'api');
        $user->assignRole($role);
        $user->refresh();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $user;
    }

    public function test_user_can_get_warehouse_inventory(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
            'stock' => 100,
        ]);

        $response = $this->getJson("/api/v1/warehouse/warehouses/{$warehouse->id}/inventory");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'stock'],
                ],
            ]);
    }

    public function test_warehouse_inventory_is_cached(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        // First request
        $response1 = $this->getJson("/api/v1/warehouse/warehouses/{$warehouse->id}/inventory");
        $response1->assertStatus(200);

        // Verify cache exists
        $this->assertTrue(Cache::has("warehouse_{$warehouse->id}_inventory"));

        // Second request should use cache
        $response2 = $this->getJson("/api/v1/warehouse/warehouses/{$warehouse->id}/inventory");
        $response2->assertStatus(200);
    }
}
