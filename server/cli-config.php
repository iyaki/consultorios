<?php

declare(strict_types=1);

use Consultorio\Core\CoreContainer;
use Consultorios\Core\Common\Infrastructure\DBAL;
use Consultorios\Core\Common\Infrastructure\DoctrineSettings;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$em = (new DBAL(new DoctrineSettings(
    require __DIR__ . '/app/Core/config/database.php',
    [__DIR__ . '/app/Core/Agendas/config/mappings'],
    true
)))->entityManager();

return ConsoleRunner::createHelperSet($em);
