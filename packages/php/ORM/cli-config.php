<?php

declare(strict_types=1);

namespace Consultorios\ORM;

use Consultorios\ORM\ORM;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;

if (! \function_exists('\Consultorios\DevToolsSettings\getDoctrineCliConfig')) {

    function getDoctrineCliConfig(string $baseDir): HelperSet{
        $em = (new ORM(
            require $baseDir . '/config/database.php',
            [$baseDir . '/config/mappings'],
            true
        ))->entityManager();

        return ConsoleRunner::createHelperSet($em);
    }

}
