<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Dominio;

final class Especialidad
{
    private string $id;

    private string $nombre;

    public function __construct(EspecialidadId $id, string $nombre)
    {
        $this->id = (string) $id;
        if (trim($nombre) === '') {
            throw new \DomainException('El nombre de las especialidades no puede ser vacío');
        }
        $this->nombre = $nombre;
    }

    public function id(): EspecialidadId
    {
        return new EspecialidadId($this->id);
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function renombrar(string $nombre): void
    {
        $this->nombre = $nombre;
    }
}
