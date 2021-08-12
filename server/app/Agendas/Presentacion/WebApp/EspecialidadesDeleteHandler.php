<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Core\Presentacion\WebApp\UriPathSegmentsHelper;
use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesDeleteHandler implements RequestHandlerInterface
{
    use UriPathSegmentsHelper;

    public function __construct(
        private WebAppResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $this->getId($request);
        $this->especialidades->eliminar(new EspecialidadId($id));

        return $this->responseFactory->createResponseFromItem(null, 200);
    }
}
