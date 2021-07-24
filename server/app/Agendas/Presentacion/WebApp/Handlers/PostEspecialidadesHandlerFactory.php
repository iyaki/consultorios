<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp\Handlers;

use Consultorio\Agendas\AgendasContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class PostEspecialidadesHandlerFactory
{
    public function __invoke(ContainerInterface $container): PostEspecialidadesHandler
    {
        /** @var AgendasContainer $agendasContainer */
        $agendasContainer = $container->get(AgendasContainer::class);

        /** @var ResponseFactoryInterface $responseFactory */
        $responseFactory = $container->get(ResponseFactoryInterface::class);

        return new PostEspecialidadesHandler(
            $responseFactory,
            $agendasContainer->getCasosDeUsoEspecialidades()
        );
    }
}
