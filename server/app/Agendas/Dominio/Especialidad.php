<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Dominio;

final class Especialidad
{
    private EspecialidadId $id;

    private string $nombre;

    public function __construct(EspecialidadId $id, string $nombre)
    {
        $this->id = $id;
        if (trim($nombre) === '') {
            throw new \DomainException('El nombre de las especialidades no puede ser vacío');
        }
        $this->nombre = $nombre;
    }
}
