<?php

declare(strict_types=1);

use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandler;
use Mezzio\Application;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;

return static function (Application $app): void {
    $app->pipe(ServerUrlMiddleware::class);

    $app->pipe(RouteMiddleware::class);

    $app->pipe(DispatchMiddleware::class);

    $app->get('/', HomeHandler::class);
};
