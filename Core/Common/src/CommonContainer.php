<?php

declare(strict_types=1);

namespace Consultorios\Core\Common;

use Consultorios\Core\Common\Infrastructure\DBAL;
use Consultorios\Core\Common\Infrastructure\DoctrineSettings;

final class CommonContainer
{
    public static function devMode(): bool
    {
        return (bool) getenv('DEV_MODE');
    }
}