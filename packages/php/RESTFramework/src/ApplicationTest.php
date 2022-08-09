<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Consultorios\RESTFramework\Fixtures\TestGetHandler;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @internal Test class
 *
 * @runTestsInSeparateProcesses
 */
final class ApplicationTest extends TestCase
{
    private const HOST = 'https://127.0.0.1';

    public function testOpenApi(): void
    {
        $emitter = $this->emitter();

        $app = $this->app(
            $emitter,
            $this->request(
                'GET',
                self::HOST . '/openapi'
            )
        );

        $app->run();

        $this->assertStatusCode(200, $emitter->lastEmittedResponse);
        $this->assertBody(file_get_contents(__DIR__ . '/../fixtures/openapi'), $emitter->lastEmittedResponse);
        $this->assertHeader('Content-Type', 'application/x-yaml', $emitter->lastEmittedResponse);
    }

    public function testPathAndMethodOKDevMode(): void
    {
        $emitter = $this->emitter();

        $app = $this->app(
            $emitter,
            $this->request(
                'GET',
                self::HOST . '/test'
            )
        );

        $app->run();

        $this->assertStatusCode(200, $emitter->lastEmittedResponse);
        $this->assertBody('Hey!', $emitter->lastEmittedResponse);
        $this->assertHeader('Content-Type', 'text/plain', $emitter->lastEmittedResponse);
    }

    public function testPathNotOkDevMode(): void
    {
        $emitter = $this->emitter();

        $app = $this->app(
            $emitter,
            $this->request(
                'GET',
                self::HOST . '/cualquiera'
            )
        );

        $this->expectException(\League\OpenAPIValidation\PSR15\Exception\InvalidServerRequestMessage::class);

        $app->run();
    }

    public function testMethodNotOkDevMode(): void
    {
        $emitter = $this->emitter();

        $app = $this->app(
            $emitter,
            $this->request(
                'POST',
                self::HOST . '/cualquiera'
            )
        );

        $this->expectException(\League\OpenAPIValidation\PSR15\Exception\InvalidServerRequestMessage::class);

        $app->run();
    }

    public function testPathNotOkNoDevMode(): void
    {
        $emitter = $this->emitter();

        putenv('DEV_MODE=0');

        $app = $this->app(
            $emitter,
            $this->request(
                'GET',
                self::HOST . '/cualquiera'
            )
        );

        $app->run();

        $this->assertStatusCode(404, $emitter->lastEmittedResponse);
        $this->assertBody('Cannot GET ' . self::HOST .'/cualquiera', $emitter->lastEmittedResponse);
    }

    public function testMethodNotOkNoDevMode(): void
    {
        $emitter = $this->emitter();

        putenv('DEV_MODE=0');

        $app = $this->app(
            $emitter,
            $this->request(
                'POST',
                self::HOST . '/test'
            )
        );

        $app->run();

        $this->assertSame(405, $emitter->lastEmittedResponse->getStatusCode());
        $this->assertSame('', (string) $emitter->lastEmittedResponse->getBody());
    }

    public function testOptionsCorsNoDevMode(): void
    {
        $emitter = $this->emitter();

        putenv('DEV_MODE=0');

        $app = $this->app(
            $emitter,
            $this->request(
                'OPTIONS',
                self::HOST . '/test'
            )
        );

        $app->run();

        $this->assertSame(200, $emitter->lastEmittedResponse->getStatusCode());
        $this->assertSame('', (string) $emitter->lastEmittedResponse->getBody());
        $this->assertHeader('Allow', 'GET', $emitter->lastEmittedResponse);
        $this->assertHeader('Vary', 'Origin', $emitter->lastEmittedResponse);
    }

    private function app(EmitterInterface $emitter, ServerRequestInterface $request): Application
    {
        return new Application(
            $this->routesConfigurator(...),
            __DIR__ . '/../fixtures/',
            '/',
            $this->containerConfigurator($emitter, $request)
        );
    }

    private function routesConfigurator(RoutesConfigurator $routesConfigurator): void
    {
        $routesConfigurator->get('test', static fn(): TestGetHandler => new TestGetHandler());
    }

    private function request(string $method, string $uri): ServerRequestInterface
    {
        return new ServerRequest(
            [],
            [],
            $uri,
            $method
        );
    }

    /**
     * @return \Closure(): ServerRequestInterface
     */
    private function serverRequestFactory(ServerRequestInterface $request): \Closure
    {
        return static fn (): ServerRequestInterface => $request;
    }

    private function emitter(): object
    {
        return new class() implements EmitterInterface {
            public ?ResponseInterface $lastEmittedResponse = null;

            public function emit(ResponseInterface $response): bool
            {
                return (bool) ($this->lastEmittedResponse = $response);
            }
        };
    }

    /**
     * @return \Closure(ServiceManager): void
     */
    private function containerConfigurator(EmitterInterface $emitter, ServerRequestInterface $request): \Closure
    {
        return function (ServiceManager $container) use ($emitter, $request): void {
            $container->setAllowOverride(true);
            $container->setService(
                ServerRequestInterface::class,
                $this->serverRequestFactory($request)
            );
            $container->setService(
                EmitterInterface::class,
                $emitter
            );
        };
    }

    private function assertStatusCode(int $expected, ResponseInterface $response): void
    {
        $this->assertSame($expected, $response->getStatusCode());
    }

    private function assertBody(string $expected, ResponseInterface $response): void
    {
        $this->assertSame($expected, (string) $response->getBody());
    }

    private function assertHeader(string $headerLine, string $expected, ResponseInterface $response): void
    {
        $this->assertStringContainsString($expected, $response->getHeaderLine($headerLine));
    }
}
