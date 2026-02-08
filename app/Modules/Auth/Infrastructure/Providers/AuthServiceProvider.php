<?php

namespace App\Modules\Auth\Infrastructure\Providers;

use App\Modules\Auth\Domain\Contracts\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Domain\Contracts\Services\RoleServiceInterface;
use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use App\Modules\Auth\Infrastructure\Services\SpatieRoleService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class , UserRepository::class);
        $this->app->bind(RoleServiceInterface::class , SpatieRoleService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}
