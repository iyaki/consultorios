<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;

final class OpenApiGenerator
{
    private readonly OpenApi $openApi;

    public function __construct(string $documentationPath)
    {
        $openApi = Generator::scan([__DIR__ . '/../', $documentationPath]);

        if (! $openApi instanceof OpenApi) {
            throw new \Exception('Error procesando la documentación OpenAPI');
        }

        $this->openApi = $openApi;
    }

    public function toYaml(
        string $serverHost,
        string $uriOpenApi
    ): string {
        return str_replace(
            [
                'SERVER_HOST_PLACEHOLDER',
                'URI_OPENAPI_PATH_YAML_PLACEHOLDER'
            ],
            [
                "'{$serverHost}'",
                $uriOpenApi
            ],
            $this->openApi->toYaml()
        );
    }
}
