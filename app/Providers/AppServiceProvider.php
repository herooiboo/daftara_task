<?php

namespace App\Providers;

use App\Infrastructure\Scramble\DustFormRequestParametersExtractor;
use App\Modules\Audit\Infrastructure\Providers\AuditServiceProvider;
use App\Modules\Auth\Infrastructure\Providers\AuthServiceProvider;
use App\Modules\Notifications\Infrastructure\Providers\NotificationServiceProvider;
use App\Modules\Warehouse\Infrastructure\Providers\WarehouseServiceProvider;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(WarehouseServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(AuditServiceProvider::class);
    }

    public function boot(): void
    {
        Scramble::configure()->parametersExtractors->prepend(
            DustFormRequestParametersExtractor::class,
        );

        Scramble::resolveTagsUsing(function ($routeInfo) {
            $class = $routeInfo->className();

            if (! $class) {
                return [];
            }

            $reflection = new \ReflectionClass($class);
            $docComment = $reflection->getDocComment();

            if ($docComment && preg_match('/@tags\s+(.+)/', $docComment, $matches)) {
                return [trim($matches[1])];
            }

            return [Str::of(class_basename($class))
                ->replace('Controller', '')
                ->headline()
                ->toString()];
        });

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer', 'bearerAuth'),
            );
        });
    }
}
