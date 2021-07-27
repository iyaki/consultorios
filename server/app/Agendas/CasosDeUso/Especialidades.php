<?php

declare(strict_types=1);

namespace Consultorio\Agendas\CasosDeUso;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Agendas\Dominio\EspecialidadRepositoryInterface;
use Consultorio\Core\Aplicacion\UnitOfWorkInterface;

final class Especialidades
{
    public function __construct(
        private UnitOfWorkInterface $unitOfWork,
        private EspecialidadRepositoryInterface $especialidadRepository
    ) {
    }

    public function crear(EspecialidadDTO $especialidadDTO): EspecialidadDTO
    {
        try {
            $this->unitOfWork->beginTransaction();

            $nombre = $especialidadDTO->nombre();

            $this->assertEsNombreUnico($nombre);

            $especialidadId = $this->especialidadRepository->crearId();
            $especialidad = new Especialidad($especialidadId, $nombre);
            $this->especialidadRepository->add($especialidad);

            $this->unitOfWork->commit();

            return EspecialidadDTO::fromEntity($especialidad);
        } catch (\Throwable $throwable) {
            $this->unitOfWork->rollback();
            throw $throwable;
        }
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

    private function assertEsNombreUnico(string $nombre): void
    {
        $especialidades = $this->especialidadRepository->findBy([
            'nombre' => $nombre,
        ]);

        if (count($especialidades) > 0) {
            throw new \Exception('Ya existe una especialidad con el nombre: ' . $nombre);
        }
    }
}
