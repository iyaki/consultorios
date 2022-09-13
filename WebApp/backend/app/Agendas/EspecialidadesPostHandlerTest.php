<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\UseCases\EspecialidadesTestDouble;
use Consultorios\RESTFramework\ResponseFactory;
use Laminas\Diactoros\ResponseFactory as DiactorosResponseFactory;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal Test class
 */
final class EspecialidadesPostHandlerTest extends TestCase
{
    public function testHandleOk(): void
    {
        $request = $this->request('z');

        $handler = $this->handler();

        $response = $handler->handle($request);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            <<<JSON
            {
                "data": {
                    "id": "5",
                    "nombre": "z"
                }
            }
            JSON,
            (string) $response->getBody()
        );
    }

    public function testHandleNombreDuplicado(): void
    {
        $request = $this->request('a');

        $handler = $this->handler();

        $this->expectException(\Exception::class);

        $handler->handle($request);
    }

    /**
     * @psalm-pure
     */
    private function handler(): EspecialidadesPostHandler
    {
        return new EspecialidadesPostHandler(
            new ResponseFactory(
                new DiactorosResponseFactory(),
                [
                    Especialidad::class => EspecialidadTransformer::class,
                ]
            ),
            new EspecialidadesTestDouble()
        );
    }

    /**
     * @psalm-pure
     */
    private function request(string $nombre): ServerRequestInterface
    {
        $request = new ServerRequest(
            method: 'POST',
            uri: 'http://localhost:8080/webapp/agendas/especialidades/',
            body: 'php://memory'
        );

        $request->getBody()->write(
            <<<JSON
            {
                "data": {
                    "nombre": "{$nombre}"
                }
            }
            JSON
        );

        return $request;
    }
}
