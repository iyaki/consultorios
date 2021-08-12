<?php

declare(strict_types=1);

namespace Consultorio\Agendas\CasosDeUso;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Agendas\Dominio\EspecialidadRepositoryInterface;
use Consultorio\Core\Aplicacion\UnitOfWorkInterface;

final class Especialidades
{
    public function __construct(
        private UnitOfWorkInterface $unitOfWork,
        private EspecialidadRepositoryInterface $especialidadRepository
    ) {
    }

    /**
     * @return Especialidad[]
     */
    public function getAll(): array
    {
        return $this->especialidadRepository->findBy([]);
    }

    public function crear(string $nombre): Especialidad
    {
        try {
            $this->unitOfWork->beginTransaction();

            $especialidadId = $this->especialidadRepository->crearId();
            $especialidad = new Especialidad($especialidadId, $nombre);

            $this->assertTieneNombreUnico($especialidad);

            $this->especialidadRepository->add($especialidad);

            $this->unitOfWork->commit();

            return $especialidad;
        } catch (\Throwable $throwable) {
            $this->unitOfWork->rollback();
            throw $throwable;
        }
    }

    public function editar(EspecialidadId $id, string $nombre): Especialidad
    {
        try {
            $this->unitOfWork->beginTransaction();

            $especialidad = $this->especialidadRepository->get($id);

            $especialidad->renombrar($nombre);

            $this->assertTieneNombreUnico($especialidad);

            $this->unitOfWork->commit();

            return $especialidad;
        } catch (\Throwable $throwable) {
            $this->unitOfWork->rollback();
            throw $throwable;
        }
    }

    public function eliminar(EspecialidadId $id): void
    {
        try {
            $this->unitOfWork->beginTransaction();

            $this->especialidadRepository->remove($id);

            $this->unitOfWork->commit();
        } catch (\Throwable $throwable) {
            $this->unitOfWork->rollback();
            throw $throwable;
        }
    }

    private function assertTieneNombreUnico(Especialidad $especialidad): void
    {
        $especialidades = $this->especialidadRepository->findBy([
            'nombre' => $especialidad->nombre(),
        ]);

        foreach ($especialidades as $especialidadMismoNombre) {
            if ($especialidad !== $especialidadMismoNombre) {
                throw new \Exception('Ya existe una especialidad con el nombre: ' . $especialidad->nombre());
            }
        }
    }
}
