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
        $agendasContainer = (fn ($c): AgendasContainer => $c)($container->get(AgendasContainer::class));

        return new PostEspecialidadesHandler(
            $container->get(ResponseFactoryInterface::class),
            $agendasContainer->getCasosDeUsoEspecialidades()
        );
    }
}
