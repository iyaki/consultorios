<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\EspecialidadDTO;
use Consultorio\Agendas\Presentacion\WebApp\EspecialidadTransformerInterface;
use League\Fractal\TransformerAbstract;

final class EspecialidadTransformerFractal extends TransformerAbstract implements EspecialidadTransformerInterface
{
    /**
     * @return array<string, string|null>
     */
    public function transform(EspecialidadDTO $especialidad): array
    {
        return [
            'id' => $especialidad->id(),
            'nombre' => $especialidad->nombre(),
        ];
    }
}
