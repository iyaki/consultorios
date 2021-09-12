<?php

declare(strict_types=1);

use Consultorios\Core\Common\Infrastructure\DatabaseConnectionSettings;

return new DatabaseConnectionSettings(
    getenv('DB_HOST'),
    getenv('DB_DATABASE'),
    getenv('DB_USER'),
    getenv('DB_PASSWORD'),
);
