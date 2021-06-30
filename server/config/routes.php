<?php

declare(strict_types=1);

use Consultorio\Core\Infraestructura\Presentacion\ConfigDiscover;
use Consultorio\Core\Infraestructura\Presentacion\RoutingConfigurator;
use Mezzio\Application;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;

return static function (Application $app): void {

    $app->pipe(ServerUrlMiddleware::class);

    $app->pipe(RouteMiddleware::class);

    $app->pipe(DispatchMiddleware::class);

    $routingConfigurator = new RoutingConfigurator(new ConfigDiscover());

    $routingConfigurator->configureRoutes($app);
};
