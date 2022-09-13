<?php

declare(strict_types=1);

namespace Consultorios\WebAppTests\Agendas;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\UseCases\EspecialidadesTestDouble;
use Consultorios\RESTFramework\ResponseFactory;
use Consultorios\WebApp\Agendas\EspecialidadesPatchHandler;
use Consultorios\WebApp\Agendas\EspecialidadTransformer;
use Laminas\Diactoros\ResponseFactory as DiactorosResponseFactory;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal Test class
 */
final class EspecialidadesPatchHandlerTest extends TestCase
{
    public function testHandleOk(): void
    {
        $request = $this->request('1', 'z');

        $handler = $this->handler();

        $response = $handler->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            <<<JSON
            {
                "data": {
                    "id": "1",
                    "nombre": "z"
                }
            }
            JSON,
            (string) $response->getBody()
        );
    }

    public function testHandleIdErroneo(): void
    {
        $request = $this->request('99', 'z');

        $handler = $this->handler();

        $this->expectException(\UnexpectedValueException::class);

        $handler->handle($request);
    }

    public function testHandleNombreDuplicado(): void
    {
        $request = $this->request('1', 'c');

        $handler = $this->handler();

        $this->expectException(\Exception::class);

        $handler->handle($request);
    }

    private function handler(): EspecialidadesPatchHandler
    {
        return new EspecialidadesPatchHandler(
            new ResponseFactory(
                new DiactorosResponseFactory(),
                [
                    Especialidad::class => EspecialidadTransformer::class,
                ]
            ),
            new EspecialidadesTestDouble()
        );
    }

    private function request(string $id, string $nombre): ServerRequestInterface
    {
        $request = new ServerRequest(
            method: 'PATCH',
            uri: 'http://localhost:8080/webapp/agendas/especialidades/' . $id,
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
