<?php

declare(strict_types=1);

use Consultorios\Core\Agendas\AgendasContainer;
use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Presentations\Common\REST\Framework\ExceptionMiddleware;
use Consultorios\Presentations\Common\REST\Framework\RoutesConfigurator;
use Consultorios\Presentations\WebApp\Agendas\EspecialidadesDeleteHandler;
use Consultorios\Presentations\WebApp\Agendas\EspecialidadesGetHandler;
use Consultorios\Presentations\WebApp\Agendas\EspecialidadesPatchHandler;
use Consultorios\Presentations\WebApp\Agendas\EspecialidadesPostHandler;
use Consultorios\Presentations\WebApp\Agendas\EspecialidadTransformer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;

return static function (RoutesConfigurator $routes): void {
    $webAppUriPath = $routes->basePath();

    $transformers = [
        Especialidad::class => EspecialidadTransformer::class,
    ];

    $responseFactory = $routes->responseFactory($transformers);

    $routes = $routes->withBasePath($webAppUriPath . 'agendas/');

    $routes->pipe(fn (): ExceptionMiddleware => new ExceptionMiddleware($responseFactory));

    $especialidadesPath = 'especialidades';
    $routes->delete(
        $especialidadesPath . '/{id}',
        fn (): EspecialidadesDeleteHandler => new EspecialidadesDeleteHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
    $routes->get(
        $especialidadesPath,
        fn (): EspecialidadesGetHandler => new EspecialidadesGetHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
    $routes->patch(
        $especialidadesPath . '/{id}',
        fn (): EspecialidadesPatchHandler => new EspecialidadesPatchHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
    $routes->post(
        $especialidadesPath,
        fn (): EspecialidadesPostHandler => new EspecialidadesPostHandler(
            $responseFactory,
            AgendasContainer::getCasosDeUsoEspecialidades()
        )
    );
};
