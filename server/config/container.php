<?php

declare(strict_types=1);

use Consultorio\Core\Infraestructura\Presentacion\ConfigDiscover;
use Consultorio\Core\Infraestructura\Presentacion\ContainerAggregator;
use Laminas\ServiceManager\ServiceManager;

return (function () {
    $dependencies = [];

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

    $conatinerAggregator = new ContainerAggregator(new ConfigDiscover());

    $dependencies = array_merge_recursive($dependencies, ...$configProviders, ...$conatinerAggregator->getContainers());

    return new ServiceManager($dependencies);
})();
