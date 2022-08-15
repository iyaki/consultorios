<?php

declare(strict_types=1);

use Consultorios\RESTFramework\ExceptionMiddleware;
use Consultorios\RESTFramework\ExceptionMiddlewareFactory;
use Laminas\ServiceManager\ServiceManager;
use Psr\Http\Message\ServerRequestInterface;

return static function () {

    $dependencies = [
        'services' => [
            'config' => [
                ...require __DIR__ . '/config.php',
                ...require __DIR__ . '/cors.php',
            ]
        ],
        'factories' => [
            ExceptionMiddleware::class => ExceptionMiddlewareFactory::class,
        ]
    ];

    $configProviders = array_map(
        static fn (string $configProvider): array => (new $configProvider())()['dependencies'],
        [
            \Mezzio\ConfigProvider::class,
            \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
            \Mezzio\Router\ConfigProvider::class,
            \Mezzio\Helper\ConfigProvider::class,
            \Mezzio\Cors\ConfigProvider::class,
            \Laminas\Diactoros\ConfigProvider::class,
        ]
    );

    return new ServiceManager(
        array_merge_recursive($dependencies, ...$configProviders)
    );
};
