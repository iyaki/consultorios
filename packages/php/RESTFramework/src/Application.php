<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Consultorios\RESTFramework\Clockwork\ClockworkMiddleware;
use Consultorios\RESTFramework\OpenAPI\OpenApiGenerator;
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

    /**
     * @param \Closure(RoutesConfigurator): void $routesConfigurator
     * @param ?\Closure(ServiceManager):void) $containerConfigurator
     */
    public function __construct(
        \Closure $routesConfigurator,
        string $documentationPath,
        string $uriBasePath,
        ?\Closure $containerConfigurator = null
    ) {
        /** @var ServiceManager $container */
        $container = (require __DIR__ . '/../config/container.php')();

        if (is_callable($containerConfigurator)) {
            $containerConfigurator($container);
        }

        $this->app = $container->get(\Mezzio\Application::class);

        $responseFactory = $container->get(ResponseFactoryInterface::class);

        $devMode = (bool) ($container->get('config')['dev_mode'] ?? false);

        $this->configureRoutes(
            $routesConfigurator,
            $uriBasePath,
            $documentationPath,
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
        string $documentationPath,
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
        $this->app->get(
            $uriBasePath . 'openapi.yaml',
            static fn (): ResponseInterface => new TextResponse(
                $this->generateOpenApiYaml($documentationPath, $uriBasePath),
                200,
                [
                    'Content-Type' => 'application/x-yaml',
                ]
            )
        );

        if ($devMode) {
            /* OpenAPI Spec validation middleware for dev environments */
            $this->app->pipe(fn (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface => (
                strtolower($request->getMethod()) === 'options'
                ? $handler->handle($request)
                : (
                    $this
                        ->openApiValidationMiddleware(
                            $documentationPath,
                            $uriBasePath
                        )->process($request, $handler)
                )
            ));
        }

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

    private function generateOpenApiYaml(
        string $documentationPath,
        string $uriBasePath
    ): string {
        return (
            new OpenApiGenerator($documentationPath, $uriBasePath)
        )->toYaml();
    }

    private function openApiValidationMiddleware(
        string $documentationPath,
        string $uriBasePath
    ): MiddlewareInterface {
        return (new ValidationMiddlewareBuilder())
            ->fromYaml(
                $this->generateOpenApiYaml($documentationPath, $uriBasePath)
            )->getValidationMiddleware()
        ;
    }
}
