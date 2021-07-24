<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp\Handlers;

use Consultorio\Agendas\CasosDeUso\EspecialidadDTO;
use Consultorio\Agendas\CasosDeUso\Especialidades;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PostEspecialidadesHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode((string) $request->getBody());
        if (
            $body === null
            && json_last_error() !== JSON_ERROR_NONE
        ) {
            throw new \Exception('Error Processing Body Request');
        }

        $data = $body->data;
        $this->especialidades->crear(new EspecialidadDTO(null, $data->nombre));

        $response = $this->responseFactory->createResponse(201);
        $response->getBody()->write(json_encode([
            'data' => [],
            'status' => 'ni',
        ], JSON_THROW_ON_ERROR));
        return $response;
    }
}
