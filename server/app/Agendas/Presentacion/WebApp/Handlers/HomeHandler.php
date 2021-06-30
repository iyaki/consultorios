<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp\Handlers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HomeHandler implements RequestHandlerInterface
{
    public function __construct(
        private string $prueba
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse('Hola :) ' . $this->prueba);
    }
}
