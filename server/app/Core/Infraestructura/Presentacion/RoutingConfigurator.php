<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion;

use Mezzio\Application;

final class RoutingConfigurator
{
    public function __construct(
        private ConfigDiscover $configDiscover
    ) {
    }

    public function configureRoutes(Application $app): void
    {
        $routes = $this->getRoutesConfigs();
        foreach ($routes as $route) {
            $route($app);
        }
    }

    /**
     * @return callable[]
     */
    private function getRoutesConfigs(): array
    {
        // TODO: Implement cache strategy for production

        /** @var callable[] $routes */
        $routes = $this->configDiscover->find('routes');
        return $routes;
    }
}
