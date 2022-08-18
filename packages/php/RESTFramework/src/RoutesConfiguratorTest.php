<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Laminas\Stratigility\MiddlewarePipe;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\Application;
use Mezzio\MiddlewareContainer;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\RouteCollector;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RoutesConfiguratorTest extends TestCase
{
    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    public function __sleep()
    {
        throw new \Exception('This class can\'t be serialized');
    }

    public function testBasePath(): void
    {
        $r = new RoutesConfigurator(
            $this->createMock(Application::class),
            $this->createMock(ResponseFactoryInterface::class)
        );

        $this->assertSame('', $r->basePath());

        $newBasePath = 'asd/';

        $r = $r->withBasePath($newBasePath);

        $this->assertSame($newBasePath, $r->basePath());
    }

    public function testResponseFactory(): void
    {
        $r = new RoutesConfigurator(
            $this->createMock(Application::class),
            $this->createMock(ResponseFactoryInterface::class)
        );

        $this->assertInstanceOf(ResponseFactory::class, $r->responseFactory([]));
    }

    public function testGet(): void
    {
        $app = $this->application();

        $rouesConfigurator = $this->routesConfigurator($app);

        $path = 'holu';
        $rouesConfigurator->get($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('GET', (array) $route->getAllowedMethods());
    }

    public function testGetWithBasePath(): void
    {
        $app = $this->application();
        $basePath = '/wiiii';

        $rouesConfigurator = $this
            ->routesConfigurator($app)
            ->withBasePath($basePath)
        ;

        $path = '/holu';
        $rouesConfigurator->get($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($basePath . $path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('GET', (array) $route->getAllowedMethods());
    }

    public function testPost(): void
    {
        $app = $this->application();

        $rouesConfigurator = $this->routesConfigurator($app);

        $path = 'holu';
        $rouesConfigurator->post($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('POST', (array) $route->getAllowedMethods());
    }

    public function testPostWithBasePath(): void
    {
        $app = $this->application();
        $basePath = '/wiiii';

        $rouesConfigurator = $this
            ->routesConfigurator($app)
            ->withBasePath($basePath)
        ;

        $path = '/holu';
        $rouesConfigurator->post($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($basePath . $path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('POST', (array) $route->getAllowedMethods());
    }

    public function testPut(): void
    {
        $app = $this->application();

        $rouesConfigurator = $this->routesConfigurator($app);

        $path = 'holu';
        $rouesConfigurator->put($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('PUT', (array) $route->getAllowedMethods());
    }

    public function testPutWithBasePath(): void
    {
        $app = $this->application();
        $basePath = '/wiiii';

        $rouesConfigurator = $this
            ->routesConfigurator($app)
            ->withBasePath($basePath)
        ;

        $path = '/holu';
        $rouesConfigurator->put($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($basePath . $path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('PUT', (array) $route->getAllowedMethods());
    }

    public function testPatch(): void
    {
        $app = $this->application();

        $rouesConfigurator = $this->routesConfigurator($app);

        $path = 'holu';
        $rouesConfigurator->patch($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('PATCH', (array) $route->getAllowedMethods());
    }

    public function testPatchWithBasePath(): void
    {
        $app = $this->application();
        $basePath = '/wiiii';

        $rouesConfigurator = $this
            ->routesConfigurator($app)
            ->withBasePath($basePath)
        ;

        $path = '/holu';
        $rouesConfigurator->patch($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($basePath . $path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('PATCH', (array) $route->getAllowedMethods());
    }

    public function testDelete(): void
    {
        $app = $this->application();

        $rouesConfigurator = $this->routesConfigurator($app);

        $path = 'holu';
        $rouesConfigurator->delete($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('DELETE', (array) $route->getAllowedMethods());
    }

    public function testDeleteWithBasePath(): void
    {
        $app = $this->application();
        $basePath = '/wiiii';

        $rouesConfigurator = $this
            ->routesConfigurator($app)
            ->withBasePath($basePath)
        ;

        $path = '/holu';
        $rouesConfigurator->delete($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($basePath . $path, $route->getPath());
        $this->assertCount(1, (array) $route->getAllowedMethods());
        $this->assertContains('DELETE', (array) $route->getAllowedMethods());
    }

    public function testAny(): void
    {
        $app = $this->application();

        $rouesConfigurator = $this->routesConfigurator($app);

        $path = 'holu';
        $rouesConfigurator->any($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($path, $route->getPath());
        $this->assertSame(null, $route->getAllowedMethods());
    }

    public function testAnyWithBasePath(): void
    {
        $app = $this->application();
        $basePath = '/wiiii';

        $rouesConfigurator = $this
            ->routesConfigurator($app)
            ->withBasePath($basePath)
        ;

        $path = '/holu';
        $rouesConfigurator->any($path, $this->requestHandlerFactory(...));

        $routes = $app->getRoutes();
        $route = reset($routes);

        $this->assertSame($basePath . $path, $route->getPath());
        $this->assertSame(null, $route->getAllowedMethods());
    }

    public function testPipe(): void
    {
        $middlewarePipe = new MiddlewarePipe();
        $app = $this->application($middlewarePipe);

        $rouesConfigurator = $this->routesConfigurator($app);

        $bodyMessage = 'Middleware de prueba :)';

        $rouesConfigurator->pipe(fn () => new class($bodyMessage) implements MiddlewareInterface {
            private readonly string $bodyMessage;

            public function __construct(string $bodyMessage)
            {
                $this->bodyMessage = $bodyMessage;
            }

            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return new TextResponse($this->bodyMessage);
            }
        });

        $response = $middlewarePipe->handle(new ServerRequest());

        $this->assertSame($bodyMessage, (string) $response->getBody());
    }

    private function routesConfigurator(Application $app): RoutesConfigurator
    {
        return new RoutesConfigurator(
            $app,
            $this->createMock(ResponseFactoryInterface::class)
        );
    }

    private function application(?MiddlewarePipeInterface $middlewarePipe = null): Application
    {
        return new Application(
            new MiddlewareFactory($this->createMock(MiddlewareContainer::class)),
            $middlewarePipe ?? new MiddlewarePipe(),
            new RouteCollector($this->createMock(RouterInterface::class)),
            $this->createMock(RequestHandlerRunnerInterface::class),
        );
    }

    private function requestHandlerFactory(): RequestHandlerInterface
    {
        return new class() implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response();
            }
        };
    }
}
