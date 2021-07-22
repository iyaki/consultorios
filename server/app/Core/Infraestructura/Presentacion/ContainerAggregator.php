<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion;

final class ContainerAggregator
{
    public function __construct(
        private ConfigDiscover $configDiscover
    ) {
    }

    public function getContainers(): array
    {
        // TODO: Implement cache strategy for production
        return $this->configDiscover->find('container');
    }
}
