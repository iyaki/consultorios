<?php

declare(strict_types=1);

namespace Consultorios\ORM;

final class DatabaseConnectionSettings
{
    public function __construct(
        public string $host,
        public string $database,
        public string $user,
        public string $password,
    ) {
    }
}