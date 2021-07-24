<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp\Handlers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetEspecialidadesHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(json_encode([
            'data' => [],
            'status' => 'success',
        ], JSON_THROW_ON_ERROR));
    }
}
