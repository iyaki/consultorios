<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\Especialidad;
use League\Fractal\TransformerAbstract;

/**
 *  @OA\Schema(
 *      schema="Especialidad",
 *      description="Especialidad médica.",
 *      required={"id", "nombre"},
 *      additionalProperties=false,
 *  )
 *
 * Clase utilizada para formatear las respuestas JSON que incluyen "entidades"
 * de la clase Especialidad.
 * Esta clase depende de la libreria PHP League - Fractal.
 */
final class EspecialidadTransformer extends TransformerAbstract
{
    /**
     *  @OA\Property(
     *      property="id",
     *      description="Identificador único interno de la especialidad.",
     *      type="string",
     *      format="uuid",
     *      nullable=false,
     *      readOnly=true,
     *  ),
     *  @OA\Property(
     *      property="nombre",
     *      description="Nombre coloquial de la especialidad.",
     *      type="string",
     *      nullable=false,
     *  ),
     *
     * @return array<string, string>
     */
    public function transform(Especialidad $especialidad): array
    {
        return [
            'id' => (string) $especialidad->id(),
            'nombre' => $especialidad->nombre(),
        ];
    }
}
