<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\AgendasContainer;
use Consultorio\Agendas\Infraestructura\Presentacion\WebApp\ResponseFactoryAgendasFractal;
use Consultorio\Core\CoreContainer;
use Consultorio\Core\Presentacion\RoutesConfigurator;
use Consultorio\Core\Presentacion\WebApp\ExceptionMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;

return static function (RoutesConfigurator $routes): void {
    $container = $routes->container();

    $responseFactory = fn (): ResponseFactoryAgendasFractal => new ResponseFactoryAgendasFractal(
        $container->get(ResponseFactoryInterface::class)
    );

    $agendasContainer = fn (): AgendasContainer => new AgendasContainer(
        new CoreContainer($routes->container())
    );

    $routes = $routes->withBasePath('/agendas/webapp/');

    $routes->pipe(fn (): \Consultorio\Core\Presentacion\WebApp\ExceptionMiddleware => new ExceptionMiddleware($responseFactory()));

    $especialidadesPath = 'especialidades';
    $routes->delete(
        $especialidadesPath . '/{id}',
        fn (): \Consultorio\Agendas\Presentacion\WebApp\EspecialidadesDeleteHandler => new EspecialidadesDeleteHandler(
            $responseFactory(),
            $agendasContainer()->getCasosDeUsoEspecialidades()
        )
    );
    $routes->get(
        $especialidadesPath,
        fn (): \Consultorio\Agendas\Presentacion\WebApp\EspecialidadesGetHandler => new EspecialidadesGetHandler(
            $responseFactory(),
            $agendasContainer()->getCasosDeUsoEspecialidades()
        )
    );
    $routes->patch(
        $especialidadesPath . '/{id}',
        fn (): \Consultorio\Agendas\Presentacion\WebApp\EspecialidadesPatchHandler => new EspecialidadesPatchHandler(
            $responseFactory(),
            $agendasContainer()->getCasosDeUsoEspecialidades()
        )
    );
    $routes->post(
        $especialidadesPath,
        fn (): \Consultorio\Agendas\Presentacion\WebApp\EspecialidadesPostHandler => new EspecialidadesPostHandler(
            $responseFactory(),
            $agendasContainer()->getCasosDeUsoEspecialidades()
        )
    );
};
