<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\Presentacion\WebApp\Handlers\HomeHandler;
use Mezzio\Application;

return static function (Application $app): void {
    $app->get('/', HomeHandler::class);
};
