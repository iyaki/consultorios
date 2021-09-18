<?php

declare(strict_types=1);

namespace Consultorios\Presentations\Common\REST\Framework\OpenAPI;

use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;

final class OpenApiGenerator
{
    private OpenApi $openApi;

    public function __construct(string $documentationPath, string $uriBasePath)
    {
        $schema = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        if (! defined('SERVER_HOST')) {
            define('SERVER_HOST', sprintf('%s://%s', $schema, $host));
        }

        if (! defined('URI_OPENAPI_PATH_YAML')) {
            define('URI_OPENAPI_PATH_YAML', $uriBasePath . 'openapi.yaml');
        }

        if (! defined('URI_OPENAPI_PATH_JSON')) {
            define('URI_OPENAPI_PATH_JSON', $uriBasePath . 'openapi.json');
        }

        $this->openApi = Generator::scan([__DIR__, $documentationPath]);
    }

    public function toYaml(): string
    {
        return $this->openApi->toYaml();
    }

    public function toJson(): string
    {
        return $this->openApi->toJson();
    }
}
