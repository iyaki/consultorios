<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandler;
use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandlerFactory;

/**
 * Esta configuración es utilizada por el ServiceManager en /config/container.php según se describe en la documentación de [laminas-servicemanager](https://docs.laminas.dev/laminas-servicemanager/configuring-the-service-manager/)
 */
return [
    'factories' => [
        HomeHandler::class => HomeHandlerFactory::class,
    ],
];
