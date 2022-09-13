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
final class EspecialidadesDeleteHandlerTest extends TestCase
{
    public function testHandleOk(): void
    {
        $request = $this->request('1');

        $handler = $this->handler();

        $response = $handler->handle($request);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testHandleIdErroneo(): void
    {
        $request = $this->request('99');

        $handler = $this->handler();

        $this->expectException(\UnexpectedValueException::class);

        $handler->handle($request);
    }

    private function handler(): EspecialidadesDeleteHandler
    {
        return new EspecialidadesDeleteHandler(
            new ResponseFactory(
                new DiactorosResponseFactory(),
                [
                    Especialidad::class => EspecialidadTransformer::class,
                ]
            ),
            new EspecialidadesTestDouble()
        );
    }

    private function request(string $id): ServerRequestInterface
    {
        return new ServerRequest(
            method: 'DELETE',
            uri: 'http://localhost:8080/webapp/agendas/especialidades/' . $id
        );
    }
}
