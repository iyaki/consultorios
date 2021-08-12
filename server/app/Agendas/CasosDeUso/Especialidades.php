<?php

declare(strict_types=1);

namespace Consultorio\Agendas\CasosDeUso;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Agendas\Dominio\EspecialidadRepositoryInterface;
use Consultorio\Core\Aplicacion\UnitOfWorkInterface;

/**
 * Servicio de casos de uso existentes para trabajar con especialidades.
 * Ya que las especialidades no cumplen una función en si mismas sino que son utilizadas como parte
 * de la lógica de las Citas las operaciones disponibles en este servicio son las correspondientes a CRUD.
 */
final class Especialidades
{
    public function __construct(
        private UnitOfWorkInterface $unitOfWork,
        private EspecialidadRepositoryInterface $especialidadRepository
    ) {
    }

    /**
     * Devuelve todas las especialidades.
     *
     * @return Especialidad[]
     */
    public function getAll(): array
    {
        return $this->especialidadRepository->findBy([]);
    }

    /**
     * Crea una nueva especialidad a partir de un nombre.
     *
     * @throws \Exception excepción lanzada al intentar crear una nueva especialidad a partir de un nombre repetido.
     */
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

    /**
     * Edita el nombre de una especialidad.
     *
     * @throws \Exception excepción lanzada al intentar editar una especialidad con un nombre repetido.
     * @throws \UnexpectedValueException excepción lanzada al intentar editar una especialidad inexistente.
     */
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

    /**
     * Elimina una especialidad
     *
     * @throws \UnexpectedValueException excepción lanzada al intentar eliminar una especialidad inexistente.
     */
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
