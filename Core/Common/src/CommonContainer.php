<?php

declare(strict_types=1);

namespace Consultorios\Core\Common;

final class CommonContainer
{
    public static function devMode(): bool
    {
        return (bool) getenv('DEV_MODE')

    }
}
