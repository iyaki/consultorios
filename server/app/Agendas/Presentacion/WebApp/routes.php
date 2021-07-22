<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\Presentacion\WebApp\Handlers\GetEspecialidadesHandler;
use Consultorio\Agendas\Presentacion\WebApp\Handlers\PostEspecialidadesHandler;
use Mezzio\Application;

return static function (Application $app): void {
    $basePath = '/agendas/webapp/';

    $app->get($basePath . 'especialidades', GetEspecialidadesHandler::class);
    $app->post($basePath . 'especialidades', PostEspecialidadesHandler::class);
};
