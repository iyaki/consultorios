<?php

declare(strict_types=1);

use Consultorio\Core\Infraestructura\Presentacion\ConfigDiscover;
use Consultorio\Core\Infraestructura\Presentacion\WebApp\AbstractResponseFactoryFractal;
use Consultorio\Core\Presentacion\RoutesConfigurator;
use Consultorio\Core\Presentacion\WebApp\ExceptionMiddleware;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\Application;
use Mezzio\Cors\Middleware\CorsMiddleware;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

return static function (ServiceManager $container): void {
    /** @var Application $app */
    $app = $container->get(\Mezzio\Application::class);

    $app->pipe(ServerUrlMiddleware::class);

    $app->pipe(CorsMiddleware::class);

    $app->pipe(RouteMiddleware::class);

    $app->pipe(ImplicitHeadMiddleware::class);
    $app->pipe(ImplicitOptionsMiddleware::class);
    $app->pipe(MethodNotAllowedMiddleware::class);

    $app->get(
        '/documentation/{modulo}',
        function(ServerRequestInterface $request): ResponseInterface {
            $schema = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';
            $host = $_SERVER['HTTP_HOST'] ?? 'webserver';
            define('SERVER_HOST', "${schema}://${host}");

            $modulo = ucfirst(explode('/', $request->getUri()->getPath())[2] ?? throw new \Exception('Error Processing Request'));
            $openapi = \OpenApi\Generator::scan([__DIR__ . "/../app/${modulo}"]);
            return new TextResponse($openapi->toYaml(), 200, ['Content-Type' => 'application/x-yaml']);
        }
    );

    $routesConfigurator = new RoutesConfigurator($container);

    $configDiscover = new ConfigDiscover();
    foreach ($configDiscover->find('routes') as $routes) {
        (require $routes)($routesConfigurator);
    }

    $app->pipe(DispatchMiddleware::class);

    $app->pipe(NotFoundHandler::class);
};
