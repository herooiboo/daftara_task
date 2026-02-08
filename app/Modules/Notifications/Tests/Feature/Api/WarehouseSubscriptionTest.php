<?php

namespace App\Modules\Notifications\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Notifications\Infrastructure\Models\WarehouseNotificationSubscription;
use App\Modules\Notifications\Infrastructure\Seeders\NotificationChannelsSeeder;
use App\Modules\Warehouse\Infrastructure\Factories\WarehouseFactory;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * @group notifications
 */
class WarehouseSubscriptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(NotificationChannelsSeeder::class);
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

    public function test_superadmin_can_subscribe_users_to_warehouse_notifications(): void
    {
        $admin = $this->createUserWithRole('superadmin');
        Sanctum::actingAs($admin);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $warehouse = Warehouse::factory()->create();

        $response = $this->postJson("/api/v1/notifications/warehouses/{$warehouse->id}/notification-subscribers", [
            'user_ids' => [$user1->id, $user2->id],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'subscribed_count',
                    'subscriptions' => [
                        '*' => ['id', 'user_id', 'warehouse_id'],
                    ],
                ],
            ]);

        $this->assertDatabaseHas('warehouse_notification_subscriptions', [
            'user_id' => $user1->id,
            'warehouse_id' => $warehouse->id,
        ]);

        $this->assertDatabaseHas('warehouse_notification_subscriptions', [
            'user_id' => $user2->id,
            'warehouse_id' => $warehouse->id,
        ]);
    }

    public function test_staff_cannot_subscribe_users_to_warehouse_notifications(): void
    {
        $staff = $this->createUserWithRole('staff');
        Sanctum::actingAs($staff);

        $user = User::factory()->create();
        $warehouse = Warehouse::factory()->create();

        $response = $this->postJson("/api/v1/notifications/warehouses/{$warehouse->id}/notification-subscribers", [
            'user_ids' => [$user->id],
        ]);

        $response->assertStatus(403);
    }

    public function test_superadmin_can_get_warehouse_subscribers(): void
    {
        $admin = $this->createUserWithRole('superadmin');
        Sanctum::actingAs($admin);

        $warehouse = Warehouse::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        WarehouseNotificationSubscription::create([
            'user_id' => $user1->id,
            'warehouse_id' => $warehouse->id,
        ]);

        WarehouseNotificationSubscription::create([
            'user_id' => $user2->id,
            'warehouse_id' => $warehouse->id,
        ]);

        $response = $this->getJson("/api/v1/notifications/warehouses/{$warehouse->id}/notification-subscribers");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'user_id', 'warehouse_id'],
                ],
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_superadmin_can_unsubscribe_users_from_warehouse_notifications(): void
    {
        $admin = $this->createUserWithRole('superadmin');
        Sanctum::actingAs($admin);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $warehouse = Warehouse::factory()->create();

        $subscription1 = WarehouseNotificationSubscription::create([
            'user_id' => $user1->id,
            'warehouse_id' => $warehouse->id,
        ]);

        $subscription2 = WarehouseNotificationSubscription::create([
            'user_id' => $user2->id,
            'warehouse_id' => $warehouse->id,
        ]);

        $response = $this->deleteJson("/api/v1/notifications/warehouses/{$warehouse->id}/notification-subscribers", [
            'user_ids' => [$user1->id, $user2->id],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'unsubscribed_count',
                    'message',
                ],
            ]);

        $this->assertDatabaseMissing('warehouse_notification_subscriptions', [
            'id' => $subscription1->id,
        ]);

        $this->assertDatabaseMissing('warehouse_notification_subscriptions', [
            'id' => $subscription2->id,
        ]);
    }
}
