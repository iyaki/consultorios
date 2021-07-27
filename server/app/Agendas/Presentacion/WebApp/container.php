<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\AgendasContainer;
use Consultorio\Agendas\Infraestructura\Presentacion\WebApp\WebAppResponseFactoryAgendasFractal;
use Consultorio\Core\CoreContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * Esta configuración es utilizada por el ServiceManager en /config/container.php según se describe en la documentación de [laminas-servicemanager](https://docs.laminas.dev/laminas-servicemanager/configuring-the-service-manager/)
 */
return [
    'factories' => [
        EspecialidadesPostHandler::class => EspecialidadesPostHandlerFactory::class,
        AgendasContainer::class => function (ContainerInterface $c): AgendasContainer {
            /** @var CoreContainer $coreContainer */
            $coreContainer = $c->get(CoreContainer::class);

            return new AgendasContainer($coreContainer);
        },
        WebAppResponseFactoryAgendasInterface::class => function (ContainerInterface $c): WebAppResponseFactoryAgendasFractal {
            /** @var ResponseFactoryInterface $responseFactory */
            $responseFactory = $c->get(ResponseFactoryInterface::class);
            return new WebAppResponseFactoryAgendasFractal(
                $responseFactory
            );
        },
    ],
];
