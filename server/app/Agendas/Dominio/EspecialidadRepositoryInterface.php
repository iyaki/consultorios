<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Dominio;

interface EspecialidadRepositoryInterface
{
    public function crearId(): EspecialidadId;

    /**
     * @throws \UnexpectedValueException excepción lanzada al intentar obtener
     * una especialidad inexistente.
     */
    public function get(EspecialidadId $id): Especialidad;

    /**
     * @param array<string, mixed> $criteria
     *
     * @return Especialidad[]
     */
    public function findBy(array $criteria): array;

    public function add(Especialidad $especialidad): void;

    /**
     * @throws \UnexpectedValueException excepción lanzada al intentar eliminar
     * una especialidad inexistente.
     */
    public function remove(EspecialidadId $id): void;
}
