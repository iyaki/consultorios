<?php

declare(strict_types=1);

namespace Consultorios\Presentations\Common\REST\Framework\Clockwork;

use Clockwork\Authentication\NullAuthenticator;
use Clockwork\Storage\FileStorage;

final class Clockwork
{
    private static ?\Clockwork\Clockwork $clockwork = null;

    private function __construct()
    {
    }

    public static function instance(): \Clockwork\Clockwork
    {
        if (self::$clockwork !== null) {
            return self::$clockwork;
        }

        self::$clockwork = new \Clockwork\Clockwork();

        self::$clockwork->storage(new FileStorage(__DIR__ . '/../../../../../../.clockwork'));
        self::$clockwork->authenticator(new NullAuthenticator());

        return self::$clockwork;
    }
}
