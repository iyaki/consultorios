<?php

declare(strict_types=1);

namespace Consultorio\Agendas\CasosDeUso;

use Consultorio\Agendas\Dominio\Especialidad;

final class EspecialidadDTO
{
    public function __construct(
        private ?string $id,
        private string $nombre,
    ) {
    }

    public static function fromEntity(Especialidad $especialidad): self
    {
        return new self(
            (string) $especialidad->id(),
            $especialidad->nombre()
        );
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }
}
