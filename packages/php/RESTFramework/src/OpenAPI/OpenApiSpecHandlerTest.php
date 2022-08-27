<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @internal Test class
 */
final class OpenApiSpecHandlerTest extends TestCase
{
    public function testHandleServerWithPort(): void
    {
        $handler = new OpenApiSpecHandler(
            new ResponseFactory(),
            __DIR__ . '/../../fixtures',
            '/openapi'
        );

        $server = 'https://localhost:8080';

        $response = $handler->handle(
            new ServerRequest(
                uri: $server . '/openapi'
            )
        );

        $openApiSpec = str_replace(
            OpenApiGenerator::SERVER_HOST_PLACEHOLDER,
            sprintf('\'%s\'', $server),
            file_get_contents(__DIR__ . '/../../fixtures/openapi')
        );

        $this->assertSame($openApiSpec, (string) $response->getBody());
    }

    public function testHandleServerWithoutPort(): void
    {
        $handler = new OpenApiSpecHandler(
            new ResponseFactory(),
            __DIR__ . '/../../fixtures',
            '/openapi'
        );

        $server = 'https://localhost';

        $response = $handler->handle(
            new ServerRequest(
                uri: $server . '/openapi'
            )
        );

        $openApiSpec = str_replace(
            OpenApiGenerator::SERVER_HOST_PLACEHOLDER,
            sprintf('\'%s\'', $server),
            file_get_contents(__DIR__ . '/../../fixtures/openapi')
        );

        $this->assertSame($openApiSpec, (string) $response->getBody());
    }
}
