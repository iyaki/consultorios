<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\AgendasContainer;
use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandler;
use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandlerFactory;
use Consultorio\Agendas\Presentacion\WebApp\Handlers\PostEspecialidadesHandler;
use Consultorio\Agendas\Presentacion\WebApp\Handlers\PostEspecialidadesHandlerFactory;
use Consultorio\Core\CoreContainer;
use Psr\Container\ContainerInterface;

/**
 * Esta configuración es utilizada por el ServiceManager en /config/container.php según se describe en la documentación de [laminas-servicemanager](https://docs.laminas.dev/laminas-servicemanager/configuring-the-service-manager/)
 */
return [
    'factories' => [
        HomeHandler::class => HomeHandlerFactory::class,
        PostEspecialidadesHandler::class => PostEspecialidadesHandlerFactory::class,
        AgendasContainer::class => (fn (ContainerInterface $c): AgendasContainer => new AgendasContainer($c->get(CoreContainer::class))),
    ],
];
