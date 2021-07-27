<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesGetHandler implements RequestHandlerInterface
{
    public function __construct(
        private WebAppResponseFactoryAgendasInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->responseFactory->createResponseFromCollection(
                $this->especialidades->getAll()
            );
        } catch (\Throwable $throwable) {
            return $this->responseFactory->createResponseFromItem($throwable, 500);
        }
    }
}
