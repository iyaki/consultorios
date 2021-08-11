<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesDeleteHandler implements RequestHandlerInterface
{
    public function __construct(
        private WebAppResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $id = explode('/', $request->getUri()->getPath())[4];
            $this->especialidades->eliminar(new EspecialidadId($id));

            return $this->responseFactory->createResponseFromItem(null, 200);
        } catch (\Throwable $throwable) {
            return $this->responseFactory->createResponseFromItem($throwable, 500);
        }
    }
}
