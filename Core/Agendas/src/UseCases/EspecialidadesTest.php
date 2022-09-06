<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas\UseCases;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\Domain\EspecialidadId;
use Consultorios\Core\Agendas\Domain\EspecialidadRepositoryInterface;
use Consultorios\ORM\UnitOfWorkInterface;
use PHPUnit\Framework\TestCase;

final class EspecialidadesTest extends TestCase
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

        $expectedResults = $this->repositoryStorage;

        $this->assertEquals($results, $expectedResults);
    }

    public function testCrearOk(): void
    {
        $result = $this->especialidadesCasosDeUso()->crear(self::NOMBRE);

        $this->assertSame(self::NOMBRE, $result->nombre());
        $this->assertInstanceOf(EspecialidadId::class, $result->id());
        $especialidadGuardada = end($this->repositoryStorage);
        $this->assertEquals($result->id(), $especialidadGuardada->id());
        $this->assertSame($result->nombre(), $especialidadGuardada->nombre());
    }

    public function testCrearRepetido(): void
    {
        $nombre = reset($this->repositoryStorage)->nombre();

        $this->expectException(\Exception::class);

        $this->especialidadesCasosDeUso()->crear($nombre);
    }

    public function testEditarOk(): void
    {
        $especialidadGuardada = reset($this->repositoryStorage);

        $id = (string) $especialidadGuardada->id();
        $nombreNuevo = 'Especialidad3';

        $result = $this->especialidadesCasosDeUso()->editar(
            new EspecialidadId($id),
            $nombreNuevo
        );

        $this->assertSame($nombreNuevo, $result->nombre());
        $this->assertSame($id, (string) $result->id());
        $this->assertSame($result->nombre(), $especialidadGuardada->nombre());
    }

    public function testEditarIdErroneo(): void
    {
        $nombreNuevo = 'Especialidad3';

        $this->expectException(\UnexpectedValueException::class);

        $this->especialidadesCasosDeUso()->editar(
            new EspecialidadId(self::ID),
            $nombreNuevo
        );
    }

    public function testEditarNombreRepetido(): void
    {
        $especialidadGuardada = reset($this->repositoryStorage);
        $id = (string) $especialidadGuardada->id();

        $otraEspecialidadGuardada = end($this->repositoryStorage);
        $nombreNuevo = $otraEspecialidadGuardada->nombre();

        $this->expectException(\Exception::class);

        $this->especialidadesCasosDeUso()->editar(
            new EspecialidadId($id),
            $nombreNuevo
        );
    }

    private function especialidadesCasosDeUso(): Especialidades
    {
        $uow = $this->createStub(UnitOfWorkInterface::class);
        $repository = $this->createStub(EspecialidadRepositoryInterface::class);

        $repository->method('findBy')
            ->willReturnCallback(fn (array $criteria): array => array_filter(
                $this->repositoryStorage,
                static function (Especialidad $e) use ($criteria): bool {
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
            ->willReturnCallback(fn (EspecialidadId $id): Especialidad => array_values(array_filter(
                $this->repositoryStorage,
                static fn (Especialidad $e): bool => (string) $e->id() === (string) $id
            ))[0] ?? throw new \UnexpectedValueException())
        ;

        $repository->method('crearId')
            ->willReturnCallback(static fn (): EspecialidadId => new EspecialidadId(uniqid()))
        ;

        $repository->method('add')
            ->willReturnCallback(function (Especialidad $e): void {
                $this->repositoryStorage[] = $e;
            })
        ;

        return new Especialidades(
            $uow,
            $repository
        );
    }
}
