<?php

namespace App\Modules\Auth\Infrastructure\Providers;

use App\Modules\Auth\Domain\Contracts\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}
