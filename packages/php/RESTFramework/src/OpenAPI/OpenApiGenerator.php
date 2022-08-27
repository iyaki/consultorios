<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;

final class OpenApiGenerator
{
    public const SERVER_HOST_PLACEHOLDER = 'SERVER_HOST_PLACEHOLDER';

    public const URI_PATH_OPENAPI_YAML_PLACEHOLDER = 'URI_PATH_OPENAPI_YAML_PLACEHOLDER';

    private readonly OpenApi $openApi;

    public function __construct(string $documentationPath)
    {
        $openApi = Generator::scan([__DIR__ . '/../', $documentationPath]);

        // @codeCoverageIgnoreStart
        if (! $openApi instanceof OpenApi) {
            throw new \Exception('Error procesando la documentación OpenAPI');
        }
        // @codeCoverageIgnoreEnd

        $this->openApi = $openApi;
    }

    public function toYaml(
        string $serverHost,
        string $uriOpenApi
    ): string {
        return str_replace(
            [
                self::SERVER_HOST_PLACEHOLDER,
                self::URI_PATH_OPENAPI_YAML_PLACEHOLDER,
            ],
            [
                sprintf('\'%s\'', $serverHost),
                $uriOpenApi,
            ],
            $this->openApi->toYaml()
        );
    }
}
