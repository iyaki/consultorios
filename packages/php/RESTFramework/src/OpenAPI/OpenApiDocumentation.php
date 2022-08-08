<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use OpenApi\Attributes as OA;

/** @psalm-suppress UndefinedConstant */
#[OA\Server(url: SERVER_HOST)]
#[OA\Get(
    /** @psalm-suppress UndefinedConstant */
    path: URI_OPENAPI_PATH_YAML,
    description: 'Esta documentación',
    tags: ['Documentación']
)]
#[OA\Response(
    response: 200,
    description: 'OpenAPI YAML documentation',
    content: new OA\MediaType(
        mediaType: 'application/x-yaml',
        schema: new OA\Schema(type: 'string')
    )
)]
final class OpenApiDocumentation
{
}
