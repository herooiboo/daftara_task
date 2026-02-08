<?php

namespace App\Modules\Warehouse\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Warehouse\Infrastructure\Factories\WarehouseFactory;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * @group warehouse
 */
class WarehouseCrudTest extends TestCase
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

    public function test_user_with_permission_can_list_warehouses(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        Warehouse::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/warehouse/warehouses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'location'],
                ],
            ]);
    }

    public function test_user_without_permission_cannot_list_warehouses(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/warehouse/warehouses');

        $response->assertStatus(403);
    }

    public function test_user_with_permission_can_create_warehouse(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/warehouse/warehouses', [
            'name' => 'New Warehouse',
            'location' => 'Cairo',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'location'],
            ]);

        $this->assertDatabaseHas('warehouses', [
            'name' => 'New Warehouse',
            'location' => 'Cairo',
        ]);
    }

    public function test_user_without_permission_cannot_create_warehouse(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/warehouse/warehouses', [
            'name' => 'New Warehouse',
            'location' => 'Cairo',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_with_permission_can_view_warehouse(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();

        $response = $this->getJson("/api/v1/warehouse/warehouses/{$warehouse->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                ],
            ]);
    }

    public function test_user_with_permission_can_update_warehouse(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();

        $response = $this->putJson("/api/v1/warehouse/warehouses/{$warehouse->id}", [
            'name' => 'Updated Warehouse',
            'location' => 'Alexandria',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'name' => 'Updated Warehouse',
            'location' => 'Alexandria',
        ]);
    }

    public function test_user_without_permission_cannot_update_warehouse(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();

        $response = $this->putJson("/api/v1/warehouse/warehouses/{$warehouse->id}", [
            'name' => 'Updated Warehouse',
        ]);

        $response->assertStatus(403);
    }

    public function test_superadmin_can_delete_warehouse(): void
    {
        $user = $this->createUserWithRole('superadmin');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();

        $response = $this->deleteJson("/api/v1/warehouse/warehouses/{$warehouse->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id,
        ]);
    }

    public function test_manager_cannot_delete_warehouse(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();

        $response = $this->deleteJson("/api/v1/warehouse/warehouses/{$warehouse->id}");

        $response->assertStatus(403);
    }
}
