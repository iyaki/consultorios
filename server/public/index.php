<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    /** @var \Mezzio\Application $app */
    $app = $container->get(\Mezzio\Application::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    // (require 'config/pipeline.php')($app, $factory, $container);
    (require __DIR__ . '/../config/routes.php')($app);

    $app->run();
})();
