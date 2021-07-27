<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\AgendasContainer;
use Consultorio\Agendas\Infraestructura\Presentacion\WebApp\WebAppResponseFactoryAgendasFractal;
use Psr\Container\ContainerInterface;

final class EspecialidadesPostHandlerFactory
{
    public function __invoke(ContainerInterface $container): EspecialidadesPostHandler
    {
        /** @var AgendasContainer $agendasContainer */
        $agendasContainer = $container->get(AgendasContainer::class);

        /** @var WebAppResponseFactoryAgendasFractal $webAppTransformer */
        $webAppTransformer = $container->get(WebAppResponseFactoryAgendasInterface::class);

        return new EspecialidadesPostHandler(
            $webAppTransformer,
            $agendasContainer->getCasosDeUsoEspecialidades()
        );
    }
}
