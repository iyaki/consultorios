<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\EspecialidadDTO;

interface EspecialidadTransformerInterface
{
    /**
     * @return array<string, string|null>
     */
    public function transform(EspecialidadDTO $especialidad): array;
}
