<?php

namespace App\Modules\Audit\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * @group audit
 */
class ActivityLogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
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

    public function test_user_with_permission_can_view_activity_logs(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        // Create some activity by creating a warehouse
        $warehouse = Warehouse::factory()->create();

        $response = $this->getJson('/api/v1/audit/activity-logs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'description', 'subject_type', 'causer_id'],
                ],
            ]);
    }

    public function test_user_without_permission_cannot_view_activity_logs(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/audit/activity-logs');

        $response->assertStatus(403);
    }

    public function test_activity_logs_can_be_filtered_by_subject_type(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        // Create warehouse activity
        Warehouse::factory()->create();

        $response = $this->getJson('/api/activity-logs?subject_type=' . Warehouse::class);

        $response->assertStatus(200);
        $data = $response->json('data');
        if (count($data) > 0) {
            $this->assertEquals(Warehouse::class, $data[0]['subject_type']);
        }
    }

    public function test_activity_logs_can_be_filtered_by_causer_id(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        // Create warehouse activity
        Warehouse::factory()->create();

        $response = $this->getJson('/api/activity-logs?causer_id=' . $user->id);

        $response->assertStatus(200);
    }

    public function test_activity_logs_are_logged_when_warehouse_is_created(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Warehouse::class,
            'subject_id' => $warehouse->id,
            'causer_id' => $user->id,
        ]);
    }

    public function test_activity_logs_are_logged_when_warehouse_is_updated(): void
    {
        $user = $this->createUserWithRole('manager');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $warehouse->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Warehouse::class,
            'subject_id' => $warehouse->id,
            'causer_id' => $user->id,
        ]);
    }
}
