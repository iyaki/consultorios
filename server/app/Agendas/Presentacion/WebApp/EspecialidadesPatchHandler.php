<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesPatchHandler implements RequestHandlerInterface
{
    public function __construct(
        private WebAppResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $body = (object) json_decode((string) $request->getBody());
            if (
                $body === (new \stdClass())
                && json_last_error() !== JSON_ERROR_NONE
            ) {
                throw new \Exception('Error Processing Body Request');
            }

            $data = (object) $body->data;
            $id = explode('/', $request->getUri()->getPath())[4];
            $especialidad = $this->especialidades->editar(new EspecialidadId($id), (string) $data->nombre);

            return $this->responseFactory->createResponseFromItem($especialidad, 200);
        } catch (\Throwable $throwable) {
            return $this->responseFactory->createResponseFromItem($throwable, 500);
        }
    }
}
