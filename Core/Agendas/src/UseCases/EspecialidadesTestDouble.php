<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas\UseCases;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\Domain\EspecialidadId;

/**
 * @codeCoverageIgnore
 */
final class EspecialidadesTestDouble implements EspecialidadesInterface
{
    /**
     * @var Especialidad[]
     */
    private readonly array $especialidades;

    public function __construct()
    {
        $this->especialidades = [
            '1' => new Especialidad(
                new EspecialidadId('1'),
                'a'
            ),
            '2' => new Especialidad(
                new EspecialidadId('2'),
                'b'
            ),
            '3' => new Especialidad(
                new EspecialidadId('3'),
                'd'
            ),
            '4' => new Especialidad(
                new EspecialidadId('4'),
                'c'
            ),
        ];
    }

    public function getAll(): array
    {
        return $this->especialidades;
    }

    public function crear(string $nombre): Especialidad
    {
        $especialidad = new Especialidad(new EspecialidadId('5'), $nombre);

        $this->assertTieneNombreUnico($especialidad);

        return $especialidad;
    }

    public function editar(EspecialidadId $id, string $nombre): Especialidad
    {
        $especialidad = $this->especialidades[(string) $id] ?? throw new \UnexpectedValueException('Error');

        $especialidad->renombrar($nombre);

        $this->assertTieneNombreUnico($especialidad);

        return $especialidad;
    }

    public function eliminar(EspecialidadId $id): void
    {
        if (! isset($this->especialidades[(string) $id])) {
            throw new \UnexpectedValueException('Error');
        }
    }

    private function assertTieneNombreUnico(Especialidad $especialidadAValidar): void
    {
        foreach ($this->especialidades as $especialidad) {
            if (
                $especialidad !== $especialidadAValidar
                && $especialidad->nombre() === $especialidadAValidar->nombre()
            ) {
                throw new \Exception(
                    'Ya existe una especialidad con el nombre: ' . $especialidad->nombre()
                );
            }
        }
    }
}
