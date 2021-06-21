<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Domain;

class Agenda
{
    public function __construct(
        public ProfesionalId $profesionalId,
        public EspecialidadId $especialidadId,
        public InstitucionId $institucionId,
        public \DateTimeImmutable $inicioVigencia,
        public \DateTimeImmutable $finVigencia
    ) {
    }
}
