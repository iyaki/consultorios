<?php

declare(strict_types=1);

namespace Consultorio\Agendas\CasosDeUso;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Core\Aplicacion\UnitOfWorkInterface;

final class Especialidades
{
    public function __construct(
        private UnitOfWorkInterface $unitOfWork
    ) {
    }

    public function crear(string $nombre): void
    {
        try {
            $this->unitOfWork->beginTransaction();

            $especialidad = new Especialidad(new EspecialidadId(''), $nombre);

            $this->unitOfWork->commit();
        } catch (\Throwable $e) {
            $this->unitOfWork->rollback();
            throw $e;
        }
    }
}
