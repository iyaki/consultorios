<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\Especialidad;
// TODO: Encapsular esta dependencia dentro del package RESTFramework
use League\Fractal\TransformerAbstract;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Especialidad',
    description: 'Especialidad médica.',
    required: [
        'id',
        'nombre',
    ],
    additionalProperties: false
)]
/**
 * Clase utilizada para formatear las respuestas JSON que incluyen "entidades"
 * de la clase Especialidad.
 * Esta clase depende de la libreria PHP League - Fractal.
 */
final class EspecialidadTransformer extends TransformerAbstract
{
    /**
     * @return array{id: string, nombre: string}
     */
    #[OA\Property(
        property: 'id',
        description: 'Identificador único interno de la especialidad.',
        type: 'string',
        format: 'uuid',
        nullable: false,
        readOnly: true
    )]
    #[OA\Property(
        property: 'nombre',
        description: 'Nombre coloquial de la especialidad.',
        type: 'string',
        format: 'uuid',
        nullable: false
    )]
    public function transform(Especialidad $especialidad): array
    {
        return [
            'id' => (string) $especialidad->id(),
            'nombre' => $especialidad->nombre(),
        ];
    }
}
