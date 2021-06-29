<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandler;
use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandlerFactory;

return [
    'factories' => [
        HomeHandler::class => HomeHandlerFactory::class,
    ],
];
