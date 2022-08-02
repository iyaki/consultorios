<?php

declare(strict_types=1);

use Consultorios\RESTFramework\Application;

error_reporting(-1);
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';

(new Application(
    require __DIR__ . '/../config/routes.php',
    '/webapp/',
    __DIR__ . '/../app/'
))->run();
