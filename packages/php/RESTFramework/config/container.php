<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

return static function () {

    $dependencies = [
        'services' => [
            'config' => [
                ...require __DIR__ . '/config.php',
                ...require __DIR__ . '/cors.php',
            ]
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
