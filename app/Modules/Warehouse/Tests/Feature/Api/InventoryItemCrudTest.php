<?php

namespace App\Modules\Warehouse\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Warehouse\Infrastructure\Factories\InventoryItemFactory;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * @group warehouse
 */
class InventoryItemCrudTest extends TestCase
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

    public function test_user_with_permission_can_list_inventory_items(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        InventoryItem::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/warehouse/inventory-items');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'SKU', 'price'],
                ],
            ]);
    }

    public function test_user_with_permission_can_create_inventory_item(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/warehouse/inventory-items', [
            'name' => 'Test Item',
            'sku' => 'TEST-001',
            'price' => 99.99,
            'description' => 'Test description',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'SKU', 'price'],
            ]);

        $this->assertDatabaseHas('inventory_items', [
            'name' => 'Test Item',
            'SKU' => 'TEST-001',
        ]);
    }

    public function test_user_without_permission_cannot_create_inventory_item(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/warehouse/inventory-items', [
            'name' => 'Test Item',
            'SKU' => 'TEST-001',
            'price' => 99.99,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_view_inventory_item(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $item = InventoryItem::factory()->create();

        $response = $this->getJson("/api/v1/warehouse/inventory-items/{$item->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $item->id,
                    'name' => $item->name,
                ],
            ]);
    }

    public function test_user_with_permission_can_update_inventory_item(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $item = InventoryItem::factory()->create();

        $response = $this->putJson("/api/v1/warehouse/inventory-items/{$item->id}", [
            'name' => 'Updated Item',
            'price' => 149.99,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('inventory_items', [
            'id' => $item->id,
            'name' => 'Updated Item',
        ]);
    }

    public function test_superadmin_can_delete_inventory_item(): void
    {
        $user = $this->createUserWithRole('superadmin');
        Sanctum::actingAs($user);

        $item = InventoryItem::factory()->create();

        $response = $this->deleteJson("/api/v1/warehouse/inventory-items/{$item->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('inventory_items', [
            'id' => $item->id,
        ]);
    }
}
