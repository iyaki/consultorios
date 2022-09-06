<?php

declare(strict_types=1);

namespace Consultorios\ORM;

interface UnitOfWorkInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
