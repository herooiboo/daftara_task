<?php

namespace App\Modules\Warehouse\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * @group warehouse
 */
class CreateStockTransferTest extends TestCase
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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $user;
    }

    public function test_user_can_create_stock_transfer(): void
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

        $response = $this->postJson('/api/v1/warehouse/stock-transfers', [
            'inventory_id' => $item->id,
            'base_warehouse_id' => $baseWarehouse->id,
            'target_warehouse_id' => $targetWarehouse->id,
            'amount' => 50,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'inventory_id', 'amount'],
            ]);

        // Verify stock was deducted from base warehouse
        $this->assertDatabaseHas('warehouse_inventory_items', [
            'warehouse_id' => $baseWarehouse->id,
            'inventory_id' => $item->id,
            'stock' => 50,
        ]);

        // Verify stock was added to target warehouse
        $this->assertDatabaseHas('warehouse_inventory_items', [
            'warehouse_id' => $targetWarehouse->id,
            'inventory_id' => $item->id,
            'stock' => 50,
        ]);

        // Verify transfer record was created
        $this->assertDatabaseHas('stock_transfers', [
            'inventory_id' => $item->id,
            'base_warehouse_id' => $baseWarehouse->id,
            'target_warehouse_id' => $targetWarehouse->id,
            'amount' => 50,
        ]);
    }

    public function test_stock_transfer_fails_with_insufficient_stock(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $baseWarehouse = Warehouse::factory()->create();
        $targetWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $baseWarehouse->id,
            'inventory_id' => $item->id,
            'stock' => 10,
        ]);

        $response = $this->postJson('/api/v1/warehouse/stock-transfers', [
            'inventory_id' => $item->id,
            'base_warehouse_id' => $baseWarehouse->id,
            'target_warehouse_id' => $targetWarehouse->id,
            'amount' => 100,
        ]);

        $response->assertStatus(422);
    }

    public function test_stock_transfer_validates_warehouses_exist(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $item = InventoryItem::factory()->create();

        $response = $this->postJson('/api/v1/warehouse/stock-transfers', [
            'inventory_id' => $item->id,
            'base_warehouse_id' => 99999,
            'target_warehouse_id' => 88888,
            'amount' => 50,
        ]);

        $response->assertStatus(422);
    }

    public function test_stock_transfer_validates_inventory_item_exists(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $baseWarehouse = Warehouse::factory()->create();
        $targetWarehouse = Warehouse::factory()->create();

        $response = $this->postJson('/api/v1/warehouse/stock-transfers', [
            'inventory_id' => 99999,
            'base_warehouse_id' => $baseWarehouse->id,
            'target_warehouse_id' => $targetWarehouse->id,
            'amount' => 50,
        ]);

        $response->assertStatus(422);
    }
}
