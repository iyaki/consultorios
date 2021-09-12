<?php

declare(strict_types=1);

use Consultorios\Presentations\Common\REST\Framework\Application;

error_reporting(-1);
ini_set('display_errors', '1');

require __DIR__ . '/../../../../vendor/autoload.php';

// error_reporting(-1);
// ini_set('display_errors', '1');

// phpinfo(); exit;

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    $app = new Application(require __DIR__ . '/../config/config.php');

    $app->configureRoutes(
        require __DIR__ . '/../config/routes.php',
        '/webapp/',
        __DIR__ . '/../'
    );

    $app->run();
})();
