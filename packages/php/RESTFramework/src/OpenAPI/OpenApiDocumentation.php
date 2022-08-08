<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use OpenApi\Attributes as OA;

/** @psalm-suppress UndefinedConstant */
#[OA\Server(url: SERVER_HOST)]
#[OA\Schema(
    schema: 'Error',
    description: 'Error procesando la petición',
    required: [
        'message',
        'code',
        'file',
        'line',
        'trace',
    ],
    additionalProperties: false,
    properties: [
        new OA\Property(
            property: 'message',
            description: 'Mensaje descriptivo del error ocurrido.',
            type: 'string',
            nullable: false
        ),
        new OA\Property(
            property: 'code',
            description: 'Código indentificador del error.',
            type: 'string',
            nullable: false,
            pattern: '[0-9]+'
        ),
        new OA\Property(
            property: 'file',
            description: 'Archivo donde ocurrió el error.',
            type: 'string',
            nullable: false
        ),
        new OA\Property(
            property: 'line',
            description: 'Número de línea del archivo donde ocurrió el error.',
            type: 'number',
            nullable: false,
            format: 'int64'
        ),
        new OA\Property(
            property: 'trace',
            description: 'Lista de llamadas internas de la aplicación que finalizaron en el error.',
            type: 'string',
            nullable: false,
            format: 'int64'
        ),
    ]
)]
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
