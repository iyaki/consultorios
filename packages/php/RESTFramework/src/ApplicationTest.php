<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Consultorios\RESTFramework\Fixtures\DummyEmitter;
use Consultorios\RESTFramework\Fixtures\TestGetHandler;
use Consultorios\RESTFramework\Fixtures\TestPostHandler;
use Laminas\Diactoros\ServerRequest;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\ServiceManager\ServiceManager;
use League\OpenAPIValidation\PSR15\Exception\InvalidServerRequestMessage;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal Test class
 *
 * @runTestsInSeparateProcesses
 */
final class ApplicationTest extends TestCase
{
    /**
     * @var string
     */
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

    public function testConstructOkWithoutContainerConfigurator(): void
    {
        new Application(
            $this->routesConfigurator(...),
            __DIR__ . '/../fixtures/',
            '/',
        );

        $this->assertTrue(true);
    }

    public function testPathAndMethodOKDevMode(): void
    {
        $emitter = $this->emitter();

        putenv('DEV_MODE=1');

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

        putenv('DEV_MODE=1');

        $app = $this->app(
            $emitter,
            $this->request(
                'GET',
                self::HOST . '/cualquiera'
            )
        );

        $this->expectException(InvalidServerRequestMessage::class);

        $app->run();
    }

    public function testMethodNotOkDevMode(): void
    {
        $emitter = $this->emitter();

        putenv('DEV_MODE=1');

        $app = $this->app(
            $emitter,
            $this->request(
                'PUT',
                self::HOST . '/cualquiera'
            )
        );

        $this->expectException(InvalidServerRequestMessage::class);

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
        $this->assertBody('Cannot GET ' . self::HOST . '/cualquiera', $emitter->lastEmittedResponse);
    }

    public function testMethodNotOkNoDevMode(): void
    {
        $emitter = $this->emitter();

        putenv('DEV_MODE=0');

        $app = $this->app(
            $emitter,
            $this->request(
                'PUT',
                self::HOST . '/test'
            )
        );

        $app->run();

        $this->assertStatusCode(405, $emitter->lastEmittedResponse);
        $this->assertBody('', $emitter->lastEmittedResponse);
    }

    public function testHandlerThrowException(): void
    {
        $emitter = $this->emitter();

        putenv('DEV_MODE=1');

        $app = $this->app(
            $emitter,
            $this->request(
                'POST',
                self::HOST . '/test'
            )
        );

        $app->run();

        $this->assertStatusCode(400, $emitter->lastEmittedResponse);
        $this->assertJson((string) $emitter->lastEmittedResponse->getBody());

        $responseBody = json_decode((string) $emitter->lastEmittedResponse->getBody(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertObjectHasAttribute('data', $responseBody);
        $this->assertIsObject($responseBody->data);

        $this->assertObjectHasAttribute('message', $responseBody->data);
        $this->assertSame('Error en tiempo de ejecución', $responseBody->data->message);

        $this->assertObjectHasAttribute('code', $responseBody->data);
        $this->assertIsString($responseBody->data->code);
        $this->assertIsNumeric($responseBody->data->code);

        $this->assertObjectHasAttribute('file', $responseBody->data);
        $this->assertIsString($responseBody->data->file);

        $this->assertObjectHasAttribute('line', $responseBody->data);
        $this->assertIsInt($responseBody->data->line);

        $this->assertObjectHasAttribute('trace', $responseBody->data);
        $this->assertIsString($responseBody->data->trace);
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

        $this->assertStatusCode(200, $emitter->lastEmittedResponse);
        $this->assertBody('', $emitter->lastEmittedResponse);
        $this->assertHeader('Allow', 'GET', $emitter->lastEmittedResponse);
        $this->assertHeader('Vary', 'Origin', $emitter->lastEmittedResponse);
    }

    private function app(EmitterInterface $emitter, ServerRequestInterface $request): Application
    {
        return new Application(
            fn (RoutesConfigurator $r) => $this->routesConfigurator($r),
            __DIR__ . '/../fixtures/',
            '/',
            $this->containerConfigurator($emitter, $request)
        );
    }

    private function routesConfigurator(RoutesConfigurator $routesConfigurator): void
    {
        $routesConfigurator->get('test', static fn (): TestGetHandler => new TestGetHandler());
        $routesConfigurator->post('test', static fn (): TestPostHandler => new TestPostHandler());
    }

    private function request(string $method, string $uri): ServerRequest
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

    private function emitter(): DummyEmitter
    {
        return new DummyEmitter();
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
