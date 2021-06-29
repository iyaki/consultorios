<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp\Handlers;

final class HomeHandlerFactory
{
    public function __invoke(): HomeHandler
    {
        return new HomeHandler('bla bla bla');
    }
}
