<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Mezzio\Application;

return static function (Application $app): void {
    $basePath = '/agendas/webapp/';

    $app->get($basePath . 'especialidades', EspecialidadesGetHandler::class);
    $app->post($basePath . 'especialidades', EspecialidadesPostHandler::class);
};
