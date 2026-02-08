<?php

namespace App\Modules\Notifications\Infrastructure\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $itemName,
        protected string $itemSku,
        protected string $warehouseName,
        protected float $currentStock,
        protected float $threshold,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $greeting = property_exists($notifiable, 'name') && $notifiable->name
            ? "Hello {$notifiable->name},"
            : 'Hello,';

        return (new MailMessage)
            ->subject("Low Stock Alert: {$this->itemName}")
            ->greeting($greeting)
            ->line('This is an automated alert to inform you that stock levels have dropped below the configured threshold.')
            ->line("**Item:** {$this->itemName} (SKU: {$this->itemSku})")
            ->line("**Warehouse:** {$this->warehouseName}")
            ->line("**Current Stock:** {$this->currentStock} units")
            ->line("**Threshold:** {$this->threshold} units")
            ->line('Please take action to replenish stock as soon as possible.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'item_name' => $this->itemName,
            'item_sku' => $this->itemSku,
            'warehouse_name' => $this->warehouseName,
            'current_stock' => $this->currentStock,
            'threshold' => $this->threshold,
        ];
    }
}
