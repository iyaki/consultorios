<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

return static function (array $config) {
    $dependencies = [];

    $configProviders = array_map(
        fn (string $configProvider): array => (new $configProvider())()['dependencies'],
        [
            \Mezzio\ConfigProvider::class,
            \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
            \Mezzio\Router\ConfigProvider::class,
            \Mezzio\Helper\ConfigProvider::class,
            \Mezzio\Cors\ConfigProvider::class,
            \Laminas\Diactoros\ConfigProvider::class,
        ]
    );

    $dependencies = array_merge_recursive($dependencies, ...$configProviders);

    $dependencies['services']['config'] = array_merge_recursive(
        $config,
        require __DIR__ . '/cors.php',
    );

    return new ServiceManager($dependencies);
};