<?php

declare(strict_types=1);

namespace Consultorio\Agendas\CasosDeUso;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Agendas\Dominio\EspecialidadId;

final class EspecialidadesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    private const NOMBRE = 'Especialidad3';

    /**
     * @var string
     */
    private const ID = '55';

    /**
     * @var Especialidad[]
     */
    private array $repositoryStorage = [];

    protected function setUp(): void
    {
        $this->repositoryStorage = [
            new Especialidad(new EspecialidadId('1'), 'especialidad1'),
            new Especialidad(new EspecialidadId('2'), 'especialidad2'),
        ];
    }

    public function testGetAll(): void
    {
        $results = $this->especialidadesCasosDeUso()->getAll();

        $expectedResults = array_map(
            fn (Especialidad $e) => EspecialidadDTO::fromEntity($e),
            $this->repositoryStorage
        );
        $this->assertEquals($results, $expectedResults);
    }

    public function testCrearOk(): void
    {
        $result = $this->especialidadesCasosDeUso()->crear(new EspecialidadDTO(null, self::NOMBRE));

        $this->assertSame(self::NOMBRE, $result->nombre());
        $this->assertIsString($result->id());
        $especialidadGuardada = end($this->repositoryStorage);
        $this->assertSame($result->id(), (string) $especialidadGuardada->id());
        $this->assertSame($result->nombre(), $especialidadGuardada->nombre());
    }

    public function testCrearRepetido(): void
    {
        $nombre = reset($this->repositoryStorage)->nombre();

        $this->expectException(\Exception::class);

        $this->especialidadesCasosDeUso()->crear(new EspecialidadDTO(null, $nombre));
    }

    public function testEditarOk(): void
    {
        $especialidadGuardada = reset($this->repositoryStorage);

        $id = (string) $especialidadGuardada->id();
        $nombreNuevo = 'Especialidad3';

        $result = $this->especialidadesCasosDeUso()->editar(new EspecialidadDTO(
            $id,
            $nombreNuevo
        ));

        $this->assertSame($nombreNuevo, $result->nombre());
        $this->assertSame($id, $result->id());
        $this->assertSame($result->nombre(), $especialidadGuardada->nombre());
    }

    public function testEditarIdErroneo(): void
    {
        $nombreNuevo = 'Especialidad3';

        $this->expectException(\UnexpectedValueException::class);

        $this->especialidadesCasosDeUso()->editar(new EspecialidadDTO(
            self::ID,
            $nombreNuevo
        ));
    }

    public function testEditarNombreRepetido(): void
    {
        $especialidadGuardada = reset($this->repositoryStorage);
        $id = (string) $especialidadGuardada->id();

        $otraEspecialidadGuardada = end($this->repositoryStorage);
        $nombreNuevo = $otraEspecialidadGuardada->nombre();

        $this->expectException(\Exception::class);

        $this->especialidadesCasosDeUso()->editar(new EspecialidadDTO(
            $id,
            $nombreNuevo
        ));
    }

    private function especialidadesCasosDeUso(): Especialidades
    {
        $uow = $this->createStub(\Consultorio\Core\Aplicacion\UnitOfWorkInterface::class);
        $repository = $this->createStub(\Consultorio\Agendas\Dominio\EspecialidadRepositoryInterface::class);

        $repository->method('findBy')
            ->willReturnCallback(fn (array $criteria) => array_filter(
                $this->repositoryStorage,
                function (Especialidad $e) use ($criteria): bool {
                    foreach ($criteria as $key => $value) {
                        if ($value !== $e->{$key}()) {
                            return false;
                        }
                    }
                    return true;
                }
            ))
        ;

        $repository->method('get')
            ->willReturnCallback(fn (EspecialidadId $id) => array_values(array_filter(
                $this->repositoryStorage,
                fn (Especialidad $e) => (string) $e->id() === (string) $id
            ))[0] ?? throw new \UnexpectedValueException())
        ;

        $repository->method('crearId')
            ->willReturnCallback(fn () => new EspecialidadId(uniqid()))
        ;

        $repository->method('add')
            ->willReturnCallback(function (Especialidad $e): void {
                $this->repositoryStorage[] = $e;
            })
        ;

        return new \Consultorio\Agendas\CasosDeUso\Especialidades(
            $uow,
            $repository
        );
    }
}
