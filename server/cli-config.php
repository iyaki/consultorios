<?php

declare(strict_types=1);

use Consultorio\Core\CoreContainer;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = require_once __DIR__ . '/config/container.php';

return ConsoleRunner::createHelperSet($container->get(CoreContainer::class)->getEntityManager());
