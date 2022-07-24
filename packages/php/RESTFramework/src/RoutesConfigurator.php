<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Closure;
use Mezzio\Application;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Envoltura para la configuración de rutas de Mezzio.
 */
final class RoutesConfigurator
{
    private readonly Application $app;

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly string $basePath = ''
    ) {
        $this->app = $this->container->get(Application::class);
    }

    public function withBasePath(string $path): self
    {
        return new self($this->container, $path);
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param array<class-string, class-string> $transformers
     */
    public function responseFactory(array $transformers): ResponseFactory
    {
        return new ResponseFactory(
            $this->container->get(ResponseFactoryInterface::class),
            $transformers
        );
    }

    /**
     * @param callable(): RequestHandlerInterface $requestHandlerFactory
     */
    public function get(string $path, callable $requestHandlerFactory): void
    {
        $this->app->get(
            $this->fullPath($path),
            $this->lazyRequestHandler($requestHandlerFactory)
        );
    }

    /**
     * @param callable(): RequestHandlerInterface $requestHandlerFactory
     */
    public function post(string $path, callable $requestHandlerFactory): void
    {
        $this->app->post(
            $this->fullPath($path),
            $this->lazyRequestHandler($requestHandlerFactory)
        );
    }

    /**
     * @param callable(): RequestHandlerInterface $requestHandlerFactory
     */
    public function put(string $path, callable $requestHandlerFactory): void
    {
        $this->app->put(
            $this->fullPath($path),
            $this->lazyRequestHandler($requestHandlerFactory)
        );
    }

    /**
     * @param callable(): RequestHandlerInterface $requestHandlerFactory
     */
    public function patch(string $path, callable $requestHandlerFactory): void
    {
        $this->app->patch(
            $this->fullPath($path),
            $this->lazyRequestHandler($requestHandlerFactory)
        );
    }

    /**
     * @param callable(): RequestHandlerInterface $requestHandlerFactory
     */
    public function delete(string $path, callable $requestHandlerFactory): void
    {
        $this->app->delete(
            $this->fullPath($path),
            $this->lazyRequestHandler($requestHandlerFactory)
        );
    }

    /**
     * @param callable(): RequestHandlerInterface $requestHandlerFactory
     */
    public function any(string $path, callable $requestHandlerFactory): void
    {
        $this->app->any(
            $this->fullPath($path),
            $this->lazyRequestHandler($requestHandlerFactory)
        );
    }

    /**
     * @param callable(): MiddlewareInterface $middlewareFactory
     */
    public function pipe(callable $middlewareFactory): void
    {
        $this->app->pipe(
            $this->basePath,
            $this->lazyMiddleware($middlewareFactory)
        );
    }

    private function fullPath(string $path): string
    {
        return $this->basePath . $path;
    }

    /**
     * Este método permite que los controladores sean instanciados
     * solo cuando son necesarios
     *
     * @param callable(): RequestHandlerInterface $requestHandlerFactory
     * @return Closure(ServerRequestInterface): ResponseInterface
     */
    private function lazyRequestHandler(callable $requestHandlerFactory): Closure
    {
        return static fn (ServerRequestInterface $request): ResponseInterface => $requestHandlerFactory()->handle($request);
    }

    /**
     * Este método permite que los middlewares sean instanciados
     * solo cuando son necesarios
     *
     * @param callable(): MiddlewareInterface $middlewareFactory
     * @return Closure(ServerRequestInterface, RequestHandlerInterface): ResponseInterface
     */
    private function lazyMiddleware(callable $middlewareFactory): Closure
    {
        return static fn (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface => $middlewareFactory()->process($request, $handler);
    }
}
