<?php

declare(strict_types=1);

namespace Consultorio\Agendas;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Agendas\Infraestructura\Dominio\EspecialidadRepositoryDoctrine;
use Consultorio\Core\CoreContainer;

final class AgendasContainer
{
    public function __construct(
        private CoreContainer $coreContainer
    ) {
    }

    public function getCasosDeUsoEspecialidades(): Especialidades
    {
        return new Especialidades(
            $this->coreContainer->getUnitOfWork(),
            new EspecialidadRepositoryDoctrine($this->coreContainer->getEntityManager())
        );
    }
}
