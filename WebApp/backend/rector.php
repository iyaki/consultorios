<?php

declare(strict_types=1);

use function Consultorios\DevToolsSettings\getRectorConfigurator;

require __DIR__ . '/vendor/autoload.php';

return getRectorConfigurator([__DIR__ . '/app']);
