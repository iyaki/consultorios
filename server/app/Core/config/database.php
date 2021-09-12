<?php

declare(strict_types=1);

use Consultorios\Core\Common\Infrastructure\DatabaseConnectionSettings;

return new DatabaseConnectionSettings(
    'mariadb',
    'consultorio',
    'user',
    'password',
);
