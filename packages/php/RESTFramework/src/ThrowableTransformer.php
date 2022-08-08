<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use League\Fractal\TransformerAbstract;
use OpenApi\Attributes as OA;

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
    additionalProperties: false
)]
/**
 * Clase utilizada para formatear las respuestas JSON que incluyen excepciones
 * de la clase \Throwable.
 * Esta clase depende de la libreria PHP League - Fractal.
 */
final class ThrowableTransformer extends TransformerAbstract
{
    /**
     * @return array<string, int|string>
     *
     * @psalm-return array{message: string, code: string, file: string, line: int, trace: string}
     */
    #[OA\Property(
        property: 'message',
        description: 'Mensaje descriptivo del error ocurrido.',
        type: 'string',
        nullable: false
    )]
    #[OA\Property(
        property: 'code',
        description: 'Código indentificador del error.',
        type: 'string',
        nullable: false,
        pattern: '[0-9]+'
    )]
    #[OA\Property(
        property: 'file',
        description: 'Archivo donde ocurrió el error.',
        type: 'string',
        nullable: false
    )]
    #[OA\Property(
        property: 'line',
        description: 'Número de línea del archivo donde ocurrió el error.',
        type: 'number',
        nullable: false,
        format: 'int64'
    )]
    #[OA\Property(
        property: 'trace',
        description: 'Lista de llamadas internas de la aplicación que finalizaron en el error.',
        type: 'string',
        nullable: false,
        format: 'int64'
    )]
    public function transform(\Throwable $throwable): array
    {
        return [
            'message' => $throwable->getMessage(),
            'code' => (string) $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
        ];
    }
}
