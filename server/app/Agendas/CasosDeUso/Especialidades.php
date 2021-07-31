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
     * @return EspecialidadDTO[]
     */
    public function getAll(): array
    {
        return array_map(
            fn (Especialidad $especialidad): EspecialidadDTO => EspecialidadDTO::fromEntity($especialidad),
            $this->especialidadRepository->findBy([])
        );
    }

    public function crear(EspecialidadDTO $especialidadDTO): EspecialidadDTO
    {
        try {
            $this->unitOfWork->beginTransaction();

            $especialidadId = $this->especialidadRepository->crearId();
            $especialidad = new Especialidad($especialidadId, $especialidadDTO->nombre());

            $this->assertEsNombreUnico($especialidad);

            $this->especialidadRepository->add($especialidad);

            $this->unitOfWork->commit();

            return EspecialidadDTO::fromEntity($especialidad);
        } catch (\Throwable $throwable) {
            $this->unitOfWork->rollback();
            throw $throwable;
        }
    }

    public function editar(EspecialidadDTO $especialidadDTO): EspecialidadDTO
    {
        try {
            $this->unitOfWork->beginTransaction();

            $especialidad = $this->especialidadRepository->get(new EspecialidadId($especialidadDTO->id()));

            $especialidad->renombrar($especialidadDTO->nombre());

            $this->assertEsNombreUnico($especialidad);

            $this->unitOfWork->commit();

            return EspecialidadDTO::fromEntity($especialidad);
        } catch (\Throwable $throwable) {
            $this->unitOfWork->rollback();
            throw $throwable;
        }
    }

    public function eliminar(EspecialidadDTO $especialidadDTO): void
    {
        try {
            $this->unitOfWork->beginTransaction();

            $especialidad = $this->especialidadRepository->get(new EspecialidadId($especialidadDTO->id()));

            $this->especialidadRepository->remove($especialidad);

            $this->unitOfWork->commit();
        } catch (\Throwable $throwable) {
            $this->unitOfWork->rollback();
            throw $throwable;
        }
    }

    private function assertEsNombreUnico(Especialidad $especialidad): void
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
