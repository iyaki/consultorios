<?php

declare(strict_types=1);

use Consultorios\Core\Agendas\AgendasContainer;
use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\RESTFramework\ExceptionMiddleware;
use Consultorios\RESTFramework\RoutesConfigurator;
use Consultorios\WebApp\Agendas\EspecialidadesDeleteHandler;
use Consultorios\WebApp\Agendas\EspecialidadesGetHandler;
use Consultorios\WebApp\Agendas\EspecialidadesPatchHandler;
use Consultorios\WebApp\Agendas\EspecialidadesPostHandler;
use Consultorios\WebApp\Agendas\EspecialidadTransformer;

return static function (RoutesConfigurator $routes): void {
    $webAppUriPath = $routes->basePath();

    $transformers = [
        Especialidad::class => EspecialidadTransformer::class,
    ];

    $responseFactory = $routes->responseFactory($transformers);

    $routes = $routes->withBasePath($webAppUriPath . 'agendas/');

    $routes->pipe(static fn(): ExceptionMiddleware => new ExceptionMiddleware($responseFactory));

    $especialidadesPath = 'especialidades';
    $routes->delete(
        $especialidadesPath . '/{id}',
        static fn(): EspecialidadesDeleteHandler => new EspecialidadesDeleteHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
    $routes->get(
        $especialidadesPath,
        static fn(): EspecialidadesGetHandler => new EspecialidadesGetHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
    $routes->patch(
        $especialidadesPath . '/{id}',
        static fn(): EspecialidadesPatchHandler => new EspecialidadesPatchHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
    $routes->post(
        $especialidadesPath,
        static fn(): EspecialidadesPostHandler => new EspecialidadesPostHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
};
