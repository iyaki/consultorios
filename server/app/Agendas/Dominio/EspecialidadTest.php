<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Dominio;

use PHPUnit\Framework\TestCase;

final class EspecialidadTest extends TestCase
{
    public function testConstructorOk()
    {
        $id = new EspecialidadId('aaaaaahhhhhhhhhhhhh');
        $nombre = 'Cardiologia';
        $especialidad = new Especialidad($id, $nombre);

        $this->assertEquals($id, $especialidad->id());
        $this->assertSame($nombre, $especialidad->nombre());
    }

    public function testConstructorNombreVacio()
    {
        $this->expectException(\DomainException::class);

        new Especialidad(new EspecialidadId(''), '');
    }

    public function testConstructorNombreSoloEspacios()
    {
        $this->expectException(\DomainException::class);

        new Especialidad(new EspecialidadId(' '), '');
    }
}
