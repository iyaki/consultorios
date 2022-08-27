<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Consultorios\RESTFramework\Clockwork\ClockworkMiddleware;
use Consultorios\RESTFramework\OpenAPI\OpenApiSpecHandler;
use Consultorios\RESTFramework\OpenAPI\OpenApiValidationMiddleware;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\Cors\Middleware\CorsMiddleware;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * Envoltura para \Mezzio\Application
 */
final class Application
{
    /**
     * @var string
     */
    private const OPENAPI_PATH = 'openapi';

    private readonly \Mezzio\Application $app;

    /**
     * @param \Closure(RoutesConfigurator): void $routesConfigurator
     * @param ?\Closure(ServiceManager): void $containerConfigurator
     */
    public function __construct(
        \Closure $routesConfigurator,
        string $documentationPath,
        string $uriBasePath,
        ?\Closure $containerConfigurator = null
    ) {
        /** @var ServiceManager $container */
        $container = (require __DIR__ . '/../config/container.php')();

        $this->extraContainerConfigurations(
            $container,
            $uriBasePath,
            $documentationPath,
            $containerConfigurator
        );

        $this->app = $container->get(\Mezzio\Application::class);

        $responseFactory = $container->get(ResponseFactoryInterface::class);

        $devMode = (bool) ($container->get('config')['dev_mode'] ?? false);

        $this->configureRoutes(
            $routesConfigurator,
            $uriBasePath,
            $devMode,
            $responseFactory
        );
    }

    public function run(): void
    {
        $this->app->run();
    }

    /**
     * @param callable(RoutesConfigurator): void $configurator
     */
    private function configureRoutes(
        callable $configurator,
        string $uriBasePath,
        bool $devMode,
        ResponseFactoryInterface $responseFactory
    ): void {
        if ($devMode) {
            /* Middleware for itsgoingd/clockwork */
            $this->app->pipe(new ClockworkMiddleware());
        }

        $this->app->pipe(ServerUrlMiddleware::class);

        $this->app->pipe(CorsMiddleware::class);

        $this->app->pipe(RouteMiddleware::class);

        /* OpenAPI Spec generator based on comments (by zircote/swagger-php) */
        $this->app->get($uriBasePath . self::OPENAPI_PATH, OpenApiSpecHandler::class);

        if ($devMode) {
            /* OpenAPI Spec validation middleware for dev environments */
            $this->app->pipe(OpenApiValidationMiddleware::class);
        }

        $this->app->pipe(ExceptionMiddleware::class);

        /* User defined routes configuration */
        $configurator(new RoutesConfigurator(
            $this->app,
            $responseFactory,
            $uriBasePath
        ));

        $this->app->pipe(ImplicitOptionsMiddleware::class);
        $this->app->pipe(MethodNotAllowedMiddleware::class);

        $this->app->pipe(DispatchMiddleware::class);

        $this->app->pipe(NotFoundHandler::class);
    }

    /**
     * @param ?\Closure(ServiceManager): void $containerConfigurator
     */
    private function extraContainerConfigurations(
        ServiceManager $container,
        string $uriBasePath,
        string $documentationPath,
        ?\Closure $containerConfigurator
    ): void {
        $container->setService('documentationUri', $uriBasePath . self::OPENAPI_PATH);
        $container->setService('documentationPath', $documentationPath);

        if (is_callable($containerConfigurator)) {
            $containerConfigurator($container);
        }
    }
}
