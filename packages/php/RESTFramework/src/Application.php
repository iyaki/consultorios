<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Consultorios\RESTFramework\Clockwork\ClockworkMiddleware;
use Consultorios\RESTFramework\OpenAPI\OpenApiGenerator;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\ServiceManager\ServiceManager;
use League\OpenAPIValidation\PSR15\ValidationMiddlewareBuilder;
use Mezzio\Cors\Middleware\CorsMiddleware;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ResponseInterface;

/**
 * Envoltura para \Mezzio\Application
 */
final class Application
{
    private ServiceManager $container;

    private \Mezzio\Application $app;

    public function __construct(array $config)
    {
        $this->container = (require __DIR__ . '/../config/container.php')($config);

        $this->app = $this->container->get(\Mezzio\Application::class);
    }

    public function run(): void
    {
        $this->app->run();
    }

    /**
     * @param callable(RoutesConfigurator): void $configurator
     */
    public function configureRoutes(
        callable $configurator,
        string $uriBasePath,
        string $documentationPath
    ): void {
        $devMode = (bool) ($this->container->get('config')['dev_mode'] ?? false);

        if ($devMode) {
            $this->app->pipe(new ClockworkMiddleware());
        }

        $this->app->pipe(ServerUrlMiddleware::class);

        $this->app->pipe(CorsMiddleware::class);

        $this->app->pipe(RouteMiddleware::class);

        $this->app->get(
            $uriBasePath . 'openapi.yaml',
            fn (): ResponseInterface => new TextResponse(
                (new OpenApiGenerator($documentationPath, $uriBasePath))->toYaml(),
                200,
                [
                    'Content-Type' => 'application/x-yaml',
                ]
            )
        );

        $this->app->get(
            $uriBasePath . 'openapi.json',
            fn (): ResponseInterface => new JsonResponse(
                (new OpenApiGenerator($documentationPath, $uriBasePath))->toJson(),
                200
            )
        );

        if ($devMode && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
            $this->app->pipe(
                (new ValidationMiddlewareBuilder())->fromYaml(
                    (new OpenApiGenerator($documentationPath, $uriBasePath))->toYaml()
                )->getValidationMiddleware()
            );
        }

        $configurator(new RoutesConfigurator($this->container, $uriBasePath));

        $this->app->pipe(ImplicitOptionsMiddleware::class);
        $this->app->pipe(MethodNotAllowedMiddleware::class);

        $this->app->pipe(DispatchMiddleware::class);

        $this->app->pipe(NotFoundHandler::class);
    }
}
