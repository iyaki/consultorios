<?php

declare(strict_types=1);

namespace Consultorios\ORM;

use Consultorios\ORM\ORM;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

if (! \function_exists('\Consultorios\DevToolsSettings\getDoctrineCliConfig')) {

    function getDoctrineCliConfig(string $baseDir)
    {
        $em = (new ORM(
            require $baseDir . '/config/database.php',
            [$baseDir . '/config/mappings'],
            true
        ))->entityManager();

        return ConsoleRunner::createHelperSet($em);
    }

}
