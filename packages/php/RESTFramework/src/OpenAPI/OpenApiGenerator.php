<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;

final class OpenApiGenerator
{
    private const SERVER_HTTPS_INDEX = 'HTTPS';

    private const SERVER_HTTPS_VALUE_OFF = 'off';

    private const SERVER_HTTP_HOST_INDEX = 'HTTP_HOST';

    private const SCHEMA_HTTPS = 'https';

    private const SCHEMA_HTTP = 'http';

    private const LOCALHOST = 'localhost';

    private readonly OpenApi $openApi;

    public function __construct(string $documentationPath, string $documentationUri)
    {
        $schema = (
            (
                empty($_SERVER[self::SERVER_HTTPS_INDEX])
                || $_SERVER[self::SERVER_HTTPS_INDEX] === self::SERVER_HTTPS_VALUE_OFF
            )
            ? self::SCHEMA_HTTP
            : self::SCHEMA_HTTPS
        );
        $host = $_SERVER[self::SERVER_HTTP_HOST_INDEX] ?? self::LOCALHOST;

        if (! defined('SERVER_HOST')) {
            define('SERVER_HOST', sprintf('%s://%s', $schema, $host));
        }

        if (! defined('URI_OPENAPI_PATH_YAML')) {
            define('URI_OPENAPI_PATH_YAML', $documentationUri);
        }

        $openApi = Generator::scan([__DIR__ . '/../', $documentationPath]);

        if (! $openApi instanceof OpenApi) {
            throw new \Exception('Error procesando la documentación OpenAPI');
        }

        $this->openApi = $openApi;
    }

    public function toYaml(): string
    {
        return $this->openApi->toYaml();
    }
}
