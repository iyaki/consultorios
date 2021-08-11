<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Presentacion\WebApp;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Core\Infraestructura\Presentacion\WebApp\AbstractWebAppResponseFactoryFractal;

final class WebAppResponseFactoryAgendasFractal extends AbstractWebAppResponseFactoryFractal
{
    protected array $transformers = [
        Especialidad::class => EspecialidadTransformerFractal::class,
    ];
}
