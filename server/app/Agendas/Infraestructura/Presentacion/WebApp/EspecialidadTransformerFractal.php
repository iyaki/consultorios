<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Presentacion\WebApp;

use Consultorio\Agendas\Dominio\Especialidad;
use League\Fractal\TransformerAbstract;

final class EspecialidadTransformerFractal extends TransformerAbstract
{
    /**
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
