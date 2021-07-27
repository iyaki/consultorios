<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\EspecialidadDTO;

interface EspecialidadTransformerInterface
{
    /**
     * @return mixed[]
     */
    public function transform(EspecialidadDTO $especialidad): array;
}
