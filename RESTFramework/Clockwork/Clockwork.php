<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\Clockwork;

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

        self::$clockwork->storage(new FileStorage(self::storageFolder()));
        self::$clockwork->authenticator(new NullAuthenticator());

        return self::$clockwork;
    }

    private static function storageFolder(): string
    {
        $dir = \sys_get_temp_dir() . '/.clockworck';

        if (\file_exists($dir) && \is_dir($dir)) {
            return $dir;
        }

        if (!mkdir($dir, 0777, true)) {
            throw new \Exception('No ha sido posible crear la carpeta de clockwork');
        }

        return $dir;
    }
}
