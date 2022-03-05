<?php

declare(strict_types=1);

use function Consultorios\DevToolsSettings\getECSConfigurator;

require __DIR__ . '/vendor/autoload.php';

return getECSConfigurator([__DIR__ . '/app/']);
