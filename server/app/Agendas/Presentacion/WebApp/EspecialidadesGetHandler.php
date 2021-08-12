<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesGetHandler implements RequestHandlerInterface
{
    public function __construct(
        private WebAppResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponseFromCollection(
            $this->especialidades->getAll()
        );
    }
}
