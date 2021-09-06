<?php

declare(strict_types=1);

namespace Consultorio\Core\Presentacion;

use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;

final class OpenApiGenerator
{
    private OpenApi $openApi;

    public function __construct(string $path)
    {
        $schema = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';
        $host = $_SERVER['HTTP_HOST'] ?? 'webserver';

        if (! defined('SERVER_HOST')) {
            define('SERVER_HOST', sprintf('%s://%s', $schema, $host));
        }

        $this->openApi = Generator::scan([$path]);
    }

    public function toYaml(): string
    {
        return $this->openApi->toYaml();
    }
}
