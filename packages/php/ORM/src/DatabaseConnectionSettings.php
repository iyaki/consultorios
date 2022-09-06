<?php

declare(strict_types=1);

namespace Consultorios\ORM;

final class DatabaseConnectionSettings
{
    public function __construct(
        public readonly string $host,
        public readonly string $database,
        public readonly string $user,
        public readonly string $password,
    ) {
    }
}
