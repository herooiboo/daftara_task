<?php

use Dust\Http\Router\Enum\Router;
use Dust\Http\Router\Enum\RoutePath;

return [
    'modules' => [
        'defaults' => [
            'path' => 'app/Modules',
        ],
        'paths' => [
            'app/Modules',
        ],
    ],
    'guards' => [
        'api' => [
            'routes' => [
                'type' => Router::Attribute,
                'path' => RoutePath::None,
                'file_name' => null,
            ],
            'prefix' => 'api',
            'middleware' => 'api',
            'rate_limit_max' => 60,
        ],
        'playground' => [
            'routes' => [
                'type' => Router::File,
                'path' => RoutePath::Root,
                'file_name' => 'playground',
            ],
            'prefix' => 'playground',
            'middleware' => 'playground',
            'rate_limit_max' => 0,
        ],
    ],
    'logging' => [
        'channels' => [
            'info' => 'daily',
            'debug' => 'daily',
            'warning' => 'daily',
            'error' => 'daily',
            'emergency' => 'daily',
            'critical' => 'daily',
        ],
    ],
    'default_error_view' => 'error',
];
