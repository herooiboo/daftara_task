<?php

namespace App\Modules\Audit\Infrastructure\Providers;

use App\Modules\Audit\Domain\Contracts\Repositories\ActivityLogRepositoryInterface;
use App\Modules\Audit\Infrastructure\Repositories\ActivityLogRepository;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
