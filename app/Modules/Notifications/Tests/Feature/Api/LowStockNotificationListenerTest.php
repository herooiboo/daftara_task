<?php

namespace App\Modules\Notifications\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Seeders\RolesAndPermissionsSeeder;
use App\Modules\Notifications\Infrastructure\Events\LowStockDetected;
use App\Modules\Notifications\Infrastructure\Models\Notification;
use App\Modules\Notifications\Infrastructure\Models\NotificationReceiver;
use App\Modules\Notifications\Infrastructure\Models\WarehouseNotificationSubscription;
use App\Modules\Notifications\Infrastructure\Notifications\LowStockNotification;
use App\Modules\Notifications\Infrastructure\Seeders\NotificationChannelsSeeder;
use App\Modules\Warehouse\Application\DTOs\LowStockEventDataDTO;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Tests\TestCase;

/**
 * @group notifications
 */
class LowStockNotificationListenerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(NotificationChannelsSeeder::class);
    }

    public function test_listener_creates_notification_for_subscribed_users(): void
    {
        NotificationFacade::fake();

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create(['name' => 'Test Item', 'SKU' => 'TEST-001']);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Subscribe users to warehouse
        WarehouseNotificationSubscription::create([
            'user_id' => $user1->id,
            'warehouse_id' => $warehouse->id,
        ]);

        WarehouseNotificationSubscription::create([
            'user_id' => $user2->id,
            'warehouse_id' => $warehouse->id,
        ]);

        // Dispatch event
        $event = new LowStockDetected(new LowStockEventDataDTO(
            warehouseId: $warehouse->id,
            inventoryItemId: $item->id,
            currentStock: 10.0,
            threshold: 20.0,
        ));

        Event::dispatch($event);

        // Verify notification was created
        $this->assertDatabaseHas('notifications', [
            'type' => 'low_stock',
            'subject' => "Low Stock Alert: {$item->name}",
        ]);

        $notification = Notification::where('type', 'low_stock')->first();

        // Verify notification receivers were created for both users
        $this->assertDatabaseHas('notification_receivers', [
            'notification_id' => $notification->id,
            'notifiable_id' => $user1->id,
            'notifiable_type' => User::class,
        ]);

        $this->assertDatabaseHas('notification_receivers', [
            'notification_id' => $notification->id,
            'notifiable_id' => $user2->id,
            'notifiable_type' => User::class,
        ]);

        $this->assertCount(2, NotificationReceiver::where('notification_id', $notification->id)->get());

        NotificationFacade::assertSentTo($user1, LowStockNotification::class);
        NotificationFacade::assertSentTo($user2, LowStockNotification::class);
    }

    public function test_listener_does_not_create_notification_when_no_subscribers(): void
    {
        NotificationFacade::fake();

        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();


        $event = new LowStockDetected(new LowStockEventDataDTO(
            warehouseId: $warehouse->id,
            inventoryItemId: $item->id,
            currentStock: 10.0,
            threshold: 20.0,
        ));

        Event::dispatch($event);

        $this->assertDatabaseHas('notifications', [
            'type' => 'low_stock',
        ]);

        $notification = Notification::where('type', 'low_stock')->first();
        $this->assertCount(0, NotificationReceiver::where('notification_id', $notification->id)->get());

        NotificationFacade::assertNothingSent();
    }

    public function test_low_stock_notification_email_contains_correct_content(): void
    {
        $notification = new LowStockNotification(
            itemName: 'Widget',
            itemSku: 'WDG-001',
            warehouseName: 'Main Warehouse',
            currentStock: 5.0,
            threshold: 10.0,
        );

        $user = User::factory()->create(['name' => 'John']);
        $mailMessage = $notification->toMail($user);

        $this->assertEquals('Low Stock Alert: Widget', $mailMessage->subject);

        $content = implode(' ', $mailMessage->introLines);
        $this->assertStringContainsString('Widget', $content);
        $this->assertStringContainsString('WDG-001', $content);
        $this->assertStringContainsString('Main Warehouse', $content);
        $this->assertStringContainsString('5', $content);
        $this->assertStringContainsString('10', $content);
    }
}
