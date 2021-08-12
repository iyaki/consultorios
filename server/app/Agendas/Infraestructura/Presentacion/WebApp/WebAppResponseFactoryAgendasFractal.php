<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Presentacion\WebApp;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Core\Infraestructura\Presentacion\WebApp\AbstractWebAppResponseFactoryFractal;

/**
 * En esta clase se define (en la propieddad $transformers) la clase del transformer que debe
 * utilizarse para transoformar en respuestas de la API las distintas entidades.
 */
final class WebAppResponseFactoryAgendasFractal extends AbstractWebAppResponseFactoryFractal
{
    protected array $transformers = [
        Especialidad::class => EspecialidadTransformerFractal::class,
    ];
}
