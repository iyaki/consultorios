<?php

declare(strict_types=1);

namespace Consultorios\Core\Common;

use Consultorios\Core\Common\Infrastructure\DoctrineSettings;
use Consultorios\Core\Common\Infrastructure\DBAL;

final class CommonContainer
{
    public static function dbal(DoctrineSettings $settings): DBAL
    {
        return new DBAL($settings);
    }
}
