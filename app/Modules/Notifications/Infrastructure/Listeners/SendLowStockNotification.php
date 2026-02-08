<?php

namespace App\Modules\Notifications\Infrastructure\Listeners;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationChannelRepositoryInterface;
use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationReceiverRepositoryInterface;
use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationRepositoryInterface;
use App\Modules\Notifications\Domain\Contracts\Repositories\WarehouseNotificationSubscriptionRepositoryInterface;
use App\Modules\Notifications\Infrastructure\Events\LowStockDetected;
use App\Modules\Notifications\Infrastructure\Notifications\LowStockNotification;
use App\Modules\Warehouse\Domain\Contracts\Repositories\InventoryItemRepositoryInterface;
use App\Modules\Warehouse\Domain\Contracts\Repositories\WarehouseRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendLowStockNotification implements ShouldQueue
{
    public function __construct(
        protected WarehouseNotificationSubscriptionRepositoryInterface $warehouseNotificationSubscriptionRepository,
        protected NotificationRepositoryInterface $notificationRepository,
        protected NotificationReceiverRepositoryInterface $notificationReceiverRepository,
        protected NotificationChannelRepositoryInterface $notificationChannelRepository,
        protected WarehouseRepositoryInterface $warehouseRepository,
        protected InventoryItemRepositoryInterface $inventoryItemRepository,
    ) {}

    public function handle(LowStockDetected $event): void
    {
        $warehouse = $this->warehouseRepository->findById($event->getWarehouseId());
        $item = $this->inventoryItemRepository->findById($event->getInventoryItemId());
        $channel = $this->notificationChannelRepository->findByName('email');

        if (! $channel || ! $warehouse || ! $item) {
            return;
        }

        $notification = $this->notificationRepository->create([
            'type' => 'low_stock',
            'subject' => "Low Stock Alert: {$item->name}",
            'content' => "Stock for '{$item->name}' (SKU: {$item->SKU}) in warehouse '{$warehouse->name}' is at {$event->getCurrentStock()} units, which is at or below the threshold of {$event->getThreshold()} units.",
            'channel_id' => $channel->id,
        ]);

        $subscribers = $this->warehouseNotificationSubscriptionRepository->getByWarehouseId($event->getWarehouseId());

        $userIds = array_map(fn ($subscription) => $subscription->userId, $subscribers);
        $users = User::whereIn('id', $userIds)->get();

        foreach ($subscribers as $subscription) {
            $this->notificationReceiverRepository->create([
                'notifiable_type' => User::class,
                'notifiable_id' => $subscription->userId,
                'notification_id' => $notification->id,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }

        Log::info("Low stock notification sent for item '{$item->name}' in warehouse '{$warehouse->name}' to " . count($subscribers) . " subscribers.");

        if ($users->isNotEmpty()) {
            Notification::send(
                $users,
                new LowStockNotification(
                    itemName: $item->name,
                    itemSku: $item->SKU,
                    warehouseName: $warehouse->name,
                    currentStock: $event->getCurrentStock(),
                    threshold: $event->getThreshold(),
                ),
            );
        }
    }
}
