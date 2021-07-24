<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Dominio;

interface EspecialidadRepositoryInterface
{
    public function crearId(): EspecialidadId;

    /**
     * @return []Especialidad
     */
    public function findBy(array $criteria): array;

    public function add(Especialidad $especialidad): void;
}
