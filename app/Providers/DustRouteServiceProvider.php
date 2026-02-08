<?php

namespace App\Providers;

use ReflectionClass;
use ReflectionException;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dust\Http\Router\Attributes\Guard;
use Dust\Http\Router\Attributes\Prefix;
use Dust\Http\Router\Attributes\Middleware;
use Dust\Http\Router\Attributes\Route as RouteAttribute;
use Dust\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class DustRouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     */
    protected function registerAttributeRoutes(array $config): void
    {
        foreach (config('dust.modules.paths') as $path) {
            $modulesPath = base_path($path);
            if (! file_exists($modulesPath)) {
                return;
            }
            $modules = array_filter(scandir($modulesPath), fn ($module) => ! in_array($module, ['.', '..']));

            foreach ($modules as $module) {
                // Changed from 'Http/Controllers' to 'Presentation/Controllers' as i wanted strict DDD
                $controllersPath = implode(DIRECTORY_SEPARATOR, [$modulesPath, $module, 'Presentation', 'Controllers']);
                if (! file_exists($controllersPath)) {
                    continue;
                }

                $controllers = $this->getFiles($controllersPath);

                foreach ($controllers as $controller) {
                    $guard = null;
                    if (str_contains($controller, DIRECTORY_SEPARATOR)) {
                        [$guard, $controller] = explode(DIRECTORY_SEPARATOR, $controller);
                    }
                    $modulesRoot = str_replace('app/', '', $path);
                    $controllerName = get_module_namespace('App', $module, ['Presentation', 'Controllers', $guard, str_replace('.php', '', $controller)], $modulesRoot);
                    $this->registerControllerRoute(
                        $controllerName,
                        $config['prefix'], $config['middleware'],
                    );
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function registerControllerRoute(string $controller, string $prefix, string $middleware): void
    {
        $reflectionClass = new ReflectionClass($controller);
        $action = $reflectionClass->getName();
        $method = null;
        $route = null;
        $name = null;

        $attributes = $reflectionClass->getAttributes();
        $routeMiddleware = [];

        foreach ($attributes as $attribute) {
            switch ($attribute->getName()) {
                case Guard::class:
                    [$guard] = $attribute->getArguments();
                    if ($guard !== $middleware) {
                        return;
                    }
                    break;
                case Prefix::class:
                    [$subPrefix] = $attribute->getArguments();
                    $prefix = ! empty($prefix) ? "$prefix/$subPrefix" : $subPrefix;
                    break;
                case Middleware::class:
                    [$routeMiddleware] = $attribute->getArguments();
                    break;
                case RouteAttribute::class:
                    [$method, $route, $name] = $attribute->getArguments();
                    break;
            }
        }

        if (! $route || ! $method) {
            return;
        }

        Route::prefix($prefix)
            ->middleware([$middleware, ...$routeMiddleware])
            ->group(function (Router $router) use ($method, $route, $action, $name) {
                $controllerRoute = $router->addRoute($method->name, $route, $action);
                if ($name) {
                    $controllerRoute->name($name);
                }
            });
    }

    private function getFiles(string $path): array
    {
        $files = [];
        $list = array_filter(scandir($path), fn ($f) => ! in_array($f, ['.', '..']));

        foreach ($list as $file) {
            $filePath = $path.DIRECTORY_SEPARATOR.$file;
            if (is_dir($filePath)) {
                $files = array_merge(
                    $files,
                    array_map(fn ($f) => $file.DIRECTORY_SEPARATOR.$f, $this->getFiles($filePath)),
                );
            } else {
                $files[] = $file;
            }
        }

        return $files;
    }
}
