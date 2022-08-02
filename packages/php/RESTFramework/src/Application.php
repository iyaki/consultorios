<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Consultorios\RESTFramework\Clockwork\ClockworkMiddleware;
use Consultorios\RESTFramework\OpenAPI\OpenApiGenerator;
use Laminas\Diactoros\Response\TextResponse;
use League\OpenAPIValidation\PSR15\ValidationMiddlewareBuilder;
use Mezzio\Cors\Middleware\CorsMiddleware;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Envoltura para \Mezzio\Application
 */
final class Application
{
    private readonly \Mezzio\Application $app;

    private readonly ResponseFactoryInterface $responseFactory;

    private readonly bool $devMode;

    /**
     * @param callable(RoutesConfigurator): void $configurator
     */
    public function __construct(
        callable $configurator,
        string $uriBasePath,
        string $documentationPath
    ) {
        $container = (require __DIR__ . '/../config/container.php')();

        $this->app = $container->get(\Mezzio\Application::class);

        $this->responseFactory = $container->get(ResponseFactoryInterface::class);

        $this->devMode = (bool) ($container->get('config')['dev_mode'] ?? false);

        $this->configureRoutes(
            $configurator,
            $uriBasePath,
            $documentationPath
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
        string $documentationPath
    ): void {
        if ($this->devMode) {
            /* Middleware for itsgoingd/clockwork */
            $this->app->pipe(new ClockworkMiddleware());
        }

        $this->app->pipe(ServerUrlMiddleware::class);

        $this->app->pipe(CorsMiddleware::class);

        $this->app->pipe(RouteMiddleware::class);

        /* OpenAPI Spec generator based on comments (by zircote/swagger-php) */
        $this->app->get(
            $uriBasePath . 'openapi.yaml',
            static fn (): ResponseInterface => new TextResponse(
                (new OpenApiGenerator($documentationPath, $uriBasePath))->toYaml(),
                200,
                [
                    'Content-Type' => 'application/x-yaml',
                ]
            )
        );

        if ($this->devMode) {
            /* OpenAPI Spec validation middleware for dev environments */
            $this->app->pipe(new class($documentationPath, $uriBasePath) implements MiddlewareInterface {
                public function __construct(
                    private readonly string $documentationPath,
                    private readonly string $uriBasePath
                ) {
                }

                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    if (strtolower($request->getMethod()) === 'options') {
                        return $handler->handle($request);
                    }

                    return (new ValidationMiddlewareBuilder())->fromYaml(
                        (new OpenApiGenerator($this->documentationPath, $this->uriBasePath))->toYaml()
                    )->getValidationMiddleware()->process($request, $handler);
                }
            });
        }

        /* User defined routes configuration */
        $configurator(new RoutesConfigurator(
            $this->app,
            $this->responseFactory,
            $uriBasePath
        ));

        $this->app->pipe(ImplicitOptionsMiddleware::class);
        $this->app->pipe(MethodNotAllowedMiddleware::class);

        $this->app->pipe(DispatchMiddleware::class);

        $this->app->pipe(NotFoundHandler::class);
    }
}
