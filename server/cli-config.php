<?php

declare(strict_types=1);

use Consultorio\Core\CoreContainer;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;

/** @var Container $container */
$container = require_once __DIR__ . '/config/container.php';

return ConsoleRunner::createHelperSet($container->get(CoreContainer::class)->getEntityManager());
