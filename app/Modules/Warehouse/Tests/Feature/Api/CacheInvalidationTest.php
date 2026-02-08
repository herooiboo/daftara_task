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
class CacheInvalidationTest extends TestCase
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

    public function test_cache_is_invalidated_when_stock_is_updated(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        $stock = WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
            'stock' => 100,
        ]);

        // Populate cache
        $this->getJson("/api/v1/warehouse/warehouses/{$warehouse->id}/inventory");
        $this->assertTrue(Cache::has("warehouse_{$warehouse->id}_inventory"));

        // Update stock
        $stock->update(['stock' => 50]);

        // Cache should be invalidated
        $this->assertFalse(Cache::has("warehouse_{$warehouse->id}_inventory"));
    }

    public function test_cache_is_invalidated_when_stock_is_created(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        // Populate cache
        $this->getJson("/api/v1/warehouse/warehouses/{$warehouse->id}/inventory");
        $this->assertTrue(Cache::has("warehouse_{$warehouse->id}_inventory"));

        // Create new stock entry
        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        // Cache should be invalidated
        $this->assertFalse(Cache::has("warehouse_{$warehouse->id}_inventory"));
    }

    public function test_cache_is_invalidated_after_stock_transfer(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $baseWarehouse = Warehouse::factory()->create();
        $targetWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $baseWarehouse->id,
            'inventory_id' => $item->id,
            'stock' => 100,
        ]);

        // Populate caches
        $this->getJson("/api/warehouses/{$baseWarehouse->id}/inventory");
        $this->getJson("/api/warehouses/{$targetWarehouse->id}/inventory");

        $this->assertTrue(Cache::has("warehouse_{$baseWarehouse->id}_inventory"));
        $this->assertTrue(Cache::has("warehouse_{$targetWarehouse->id}_inventory"));

        // Create stock transfer
        $this->postJson('/api/v1/warehouse/stock-transfers', [
            'inventory_id' => $item->id,
            'base_warehouse_id' => $baseWarehouse->id,
            'target_warehouse_id' => $targetWarehouse->id,
            'amount' => 50,
        ]);

        // Both caches should be invalidated
        $this->assertFalse(Cache::has("warehouse_{$baseWarehouse->id}_inventory"));
        $this->assertFalse(Cache::has("warehouse_{$targetWarehouse->id}_inventory"));
    }
}
