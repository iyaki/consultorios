<?php

declare(strict_types=1);

use Consultorios\DBAL\DatabaseConnectionSettings;

return new DatabaseConnectionSettings(
    (string) getenv('DB_HOST'),
    (string) getenv('DB_DATABASE'),
    (string) getenv('DB_USER'),
    (string) getenv('DB_PASSWORD'),
);
