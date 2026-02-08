<?php

namespace App\Modules\Notifications\Infrastructure\Providers;

use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationChannelRepositoryInterface;
use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationReceiverRepositoryInterface;
use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationRepositoryInterface;
use App\Modules\Notifications\Domain\Contracts\Repositories\WarehouseNotificationSubscriptionRepositoryInterface;
use App\Modules\Notifications\Infrastructure\Events\LowStockDetected;
use App\Modules\Notifications\Infrastructure\Listeners\SendLowStockNotification;
use App\Modules\Notifications\Infrastructure\Repositories\NotificationChannelRepository;
use App\Modules\Notifications\Infrastructure\Repositories\NotificationReceiverRepository;
use App\Modules\Notifications\Infrastructure\Repositories\NotificationRepository;
use App\Modules\Notifications\Infrastructure\Repositories\WarehouseNotificationSubscriptionRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationChannelRepositoryInterface::class, NotificationChannelRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(NotificationReceiverRepositoryInterface::class, NotificationReceiverRepository::class);
        $this->app->bind(WarehouseNotificationSubscriptionRepositoryInterface::class, WarehouseNotificationSubscriptionRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        Event::listen(LowStockDetected::class, SendLowStockNotification::class);
    }
}
