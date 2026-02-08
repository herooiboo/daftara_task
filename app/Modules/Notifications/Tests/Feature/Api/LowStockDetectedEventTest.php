<?php

namespace App\Modules\Notifications\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Notifications\Infrastructure\Events\LowStockDetected;
use App\Modules\Notifications\Infrastructure\Seeders\NotificationChannelsSeeder;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Illuminate\Support\Facades\Event as EventFacade;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * @group notifications
 */
class LowStockDetectedEventTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(NotificationChannelsSeeder::class);
        EventFacade::fake();
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

    public function test_low_stock_event_is_dispatched_when_stock_drops_below_threshold(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
            'stock' => 30,
            'low_stock_threshold' => 20,
        ]);

        $response = $this->postJson('/api/v1/warehouse/stock-transfers', [
            'inventory_id' => $item->id,
            'base_warehouse_id' => $warehouse->id,
            'target_warehouse_id' => Warehouse::factory()->create()->id,
            'amount' => 15,
        ]);

        $response->assertStatus(201);

        EventFacade::assertDispatched(LowStockDetected::class, function ($event) use ($warehouse, $item) {
            return $event->getWarehouseId() === $warehouse->id
                && $event->getInventoryItemId() === $item->id
                && abs($event->getCurrentStock() - 15.0) < 0.01
                && abs($event->getThreshold() - 20.0) < 0.01;
        });
    }

    public function test_low_stock_event_is_not_dispatched_when_stock_above_threshold(): void
    {
        $user = $this->createUserWithRole('staff');
        Sanctum::actingAs($user);

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
            'stock' => 50,
            'low_stock_threshold' => 20,
        ]);

        $response = $this->postJson('/api/v1/warehouse/stock-transfers', [
            'inventory_id' => $item->id,
            'base_warehouse_id' => $warehouse->id,
            'target_warehouse_id' => Warehouse::factory()->create()->id,
            'amount' => 10,
        ]);

        $response->assertStatus(201);

        EventFacade::assertNotDispatched(LowStockDetected::class);
    }
}
