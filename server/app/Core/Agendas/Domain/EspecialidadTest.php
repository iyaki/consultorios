<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas\Domain;

use PHPUnit\Framework\TestCase;

final class EspecialidadTest extends TestCase
{
    /**
     * @var string
     */
    private const NOMBRE = 'Cardiologia';

    public function testConstructorOk(): void
    {
        $id = new EspecialidadId('aaaaaahhhhhhhhhhhhh');
        $especialidad = new Especialidad($id, self::NOMBRE);

        $this->assertEquals($id, $especialidad->id());
        $this->assertSame(self::NOMBRE, $especialidad->nombre());
    }

    public function testConstructorNombreVacio(): void
    {
        $this->expectException(\DomainException::class);

        new Especialidad(new EspecialidadId(''), '');
    }

    public function testConstructorNombreSoloEspacios(): void
    {
        $this->expectException(\DomainException::class);

        new Especialidad(new EspecialidadId(' '), '');
    }
}
