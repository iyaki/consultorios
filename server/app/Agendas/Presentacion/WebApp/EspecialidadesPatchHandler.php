<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Core\Presentacion\WebApp\RequestBodyHelper;
use Consultorio\Core\Presentacion\WebApp\UriPathSegmentsHelper;
use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesPatchHandler implements RequestHandlerInterface
{
    use RequestBodyHelper;
    use UriPathSegmentsHelper;

    public function __construct(
        private WebAppResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $this->getId($request);
        $data = $this->getData($request);

        $especialidad = $this->especialidades->editar(
            new EspecialidadId($id),
            (string) $data->nombre
        );

        return $this->responseFactory->createResponseFromItem($especialidad, 200);
    }
}
