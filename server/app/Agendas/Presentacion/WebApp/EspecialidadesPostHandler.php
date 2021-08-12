<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Core\Presentacion\WebApp\RequestBodyHelper;
use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesPostHandler implements RequestHandlerInterface
{
    use RequestBodyHelper;

    public function __construct(
        private WebAppResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->getData($request);

        $especialidad = $this->especialidades->crear((string) $data->nombre);

        return $this->responseFactory->createResponseFromItem($especialidad, 201);
    }
}
