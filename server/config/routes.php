<?php

declare(strict_types=1);

use Consultorio\Core\Infraestructura\Presentacion\ConfigDiscover;
use Consultorio\Core\Presentacion\RoutesConfigurator;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\Cors\Middleware\CorsMiddleware;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;

return static function (ServiceManager $container): void {
    $app = $container->get(\Mezzio\Application::class);

    $app->pipe(ServerUrlMiddleware::class);

    $app->pipe(CorsMiddleware::class);

    $app->pipe(RouteMiddleware::class);

    $app->pipe(ImplicitHeadMiddleware::class);
    $app->pipe(ImplicitOptionsMiddleware::class);
    $app->pipe(MethodNotAllowedMiddleware::class);

    $routesConfigurator = new RoutesConfigurator($container);

    $configDiscover = new ConfigDiscover();
    foreach ($configDiscover->find('routes') as $routes) {
        (require $routes)($routesConfigurator);
    }

    $app->pipe(DispatchMiddleware::class);

    $app->pipe(NotFoundHandler::class);
};
