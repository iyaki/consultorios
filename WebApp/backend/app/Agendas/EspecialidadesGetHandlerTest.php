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
final class EspecialidadesGetHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $request = $this->request();

        $handler = $this->handler();

        $response = $handler->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            <<<JSON
            {
                "data": [
                    {
                        "id": "1",
                        "nombre": "a"
                    },
                    {
                        "id": "2",
                        "nombre": "b"
                    },
                    {
                        "id": "4",
                        "nombre": "c"
                    },
                    {
                        "id": "3",
                        "nombre": "d"
                    }
                ]
            }
            JSON,
            (string) $response->getBody()
        );
    }

    /**
     * @psalm-pure
     */
    private function handler(): EspecialidadesGetHandler
    {
        return new EspecialidadesGetHandler(
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
    private function request(): ServerRequestInterface
    {
        return new ServerRequest(
            method: 'GET',
            uri: 'http://localhost:8080/webapp/agendas/especialidades/'
        );
    }
}
