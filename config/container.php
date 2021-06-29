<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

return (function () {
    $factories = [];

    $dependencies = [
        'factories' => $factories,
    ];

    $configProviders = array_map(
        /**
         * @psalm-suppress MixedInferredReturnType
         * @psalm-suppress InvalidFunctionCall
         * @psalm-suppress MixedArrayAccess
         * @psalm-suppress MixedReturnStatement
         * @psalm-suppress InvalidStringClass
         * */
        fn (string $configProvider): array => (new $configProvider())()['dependencies'],
        [
            \Mezzio\ConfigProvider::class,
            \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
            \Mezzio\Router\ConfigProvider::class,
            \Mezzio\Helper\ConfigProvider::class,
        ]
    );

    $containers = [];
    foreach (new \DirectoryIterator(__DIR__ . '/../app') as $directory) {
        if (! $directory->isDir() || $directory->isDot()) {
            continue;
        }
        $presentation = new \DirectoryIterator($directory->getPathname() . '/Presentacion');
        foreach ($presentation as $directory) {
            if (! $directory->isDir() || $directory->isDot()) {
                continue;
            }
            $containerPath = $directory->getPathname() . '/container.php';
            if (file_exists($containerPath)) {
                $containers[] = (array) require $containerPath;
            }
        }
    }

    $dependencies = array_merge_recursive($dependencies, ...$configProviders, ...$containers);

    return new ServiceManager($dependencies);
})();
