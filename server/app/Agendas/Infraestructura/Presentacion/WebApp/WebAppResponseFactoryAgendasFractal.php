<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\EspecialidadDTO;
use Consultorio\Core\Infraestructura\Presentacion\WebApp\AbstractWebAppResponseFactoryFractal;
use Psr\Http\Message\ResponseFactoryInterface;

final class WebAppResponseFactoryAgendasFractal extends AbstractWebAppResponseFactoryFractal
{
    public function __construct(
        ResponseFactoryInterface $responseFactory,
    ) {
        parent::__construct($responseFactory);

        $this->transformers = array_merge(
            $this->transformers,
            [
                EspecialidadDTO::class => EspecialidadTransformerFractal::class,
            ]
        );
    }
}
