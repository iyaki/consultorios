<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\AgendasContainer;
use Consultorio\Agendas\Infraestructura\Presentacion\WebApp\WebAppResponseFactoryAgendasFractal;
use Consultorio\Core\CoreContainer;
use Consultorio\Core\Presentacion\RoutesConfigurator;
use Psr\Http\Message\ResponseFactoryInterface;

return function (RoutesConfigurator $routes): void {
    $container = $routes->container();

    $responseFactory = fn (): WebAppResponseFactoryAgendasFractal => new WebAppResponseFactoryAgendasFractal(
        $container->get(ResponseFactoryInterface::class)
    );

    $agendasContainer = fn (): AgendasContainer => new AgendasContainer(
        new CoreContainer($routes->container())
    );

    $routes = $routes->withBasePath('/agendas/webapp/');

    $especialidadesPath = 'especialidades';
    $routes->delete($especialidadesPath . '/{id}', fn () => new EspecialidadesDeleteHandler(
        $responseFactory(),
        $agendasContainer()->getCasosDeUsoEspecialidades()
    ));
    $routes->get($especialidadesPath, fn () => new EspecialidadesGetHandler(
        $responseFactory(),
        $agendasContainer()->getCasosDeUsoEspecialidades()
    ));
    $routes->patch($especialidadesPath . '/{id}', fn () => new EspecialidadesPatchHandler(
        $responseFactory(),
        $agendasContainer()->getCasosDeUsoEspecialidades()
    ));
    $routes->post($especialidadesPath, fn () => new EspecialidadesPostHandler(
        $responseFactory(),
        $agendasContainer()->getCasosDeUsoEspecialidades()
    ));
};