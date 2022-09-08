<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas\Infrastructure;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\Domain\EspecialidadId;
use Consultorios\ORM\ObjectRepositoryInMemory;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal Test class
 */
final class EspecialidadRepositoryDoctrineTest extends TestCase
{
    public function testCrearId(): void
    {
        $repository = $this->repositoy();

        $especialidadId = $repository->crearId();

        $this->assertInstanceOf(EspecialidadId::class, $especialidadId);
        $this->assertInstanceOf(\Stringable::class, $especialidadId);
        $this->assertTrue(Uuid::isValid((string) $especialidadId));
    }

    public function testGetOk(): void
    {
        $idScalar = $this->createUUID();

        $especialidadGuardada = $this->createEspecialidad($idScalar);

        $repository = $this->repositoy([$especialidadGuardada]);

        $especialidad = $repository->get(new EspecialidadId($idScalar));

        $this->assertSame($especialidadGuardada, $especialidad);
    }

    public function testGetIdErroneo(): void
    {
        $repository = $this->repositoy();

        $this->expectException(\UnexpectedValueException::class);

        $repository->get(new EspecialidadId($this->createUUID()));
    }

    public function testFindByWithMatch(): void
    {
        $nombreEspecialidad = 'lamejorespecialidad';

        $especialidad1 = $this->createEspecialidad(null, $nombreEspecialidad);
        $especialidad2 = $this->createEspecialidad(null, $nombreEspecialidad);

        $listaEspecialidades = [
            $especialidad1,
            $especialidad2,
        ];

        $repository = $this->repositoy($listaEspecialidades);

        $especialidades = $repository->findBy([
            'nombre' => $nombreEspecialidad,
        ]);

        $this->assertIsArray($especialidades);
        $this->assertContainsOnlyInstancesOf(Especialidad::class, $especialidades);
        $this->assertCount(count($listaEspecialidades), $especialidades);
        $this->assertContains($especialidad1, $especialidades);
        $this->assertContains($especialidad2, $especialidades);
    }

    public function testFindByWithoutMatch(): void
    {
        $nombreEspecialidad = 'lamejorespecialidad';

        $repository = $this->repositoy();

        $especialidades = $repository->findBy([
            'nombre' => $nombreEspecialidad,
        ]);

        $this->assertIsArray($especialidades);
        $this->assertCount(0, $especialidades);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testAdd(): void
    {
        $this->repositoy()->add($this->createEspecialidad());
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testRemoveOk(): void
    {
        $idScalar = $this->createUUID();

        $especialidad = $this->createEspecialidad($idScalar);

        $repository = $this->repositoy([
            $especialidad,
        ]);

        $repository->remove(new EspecialidadId($idScalar));
    }

    public function testRemoveIdErroneo(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        $this->repositoy()->remove(new EspecialidadId($this->createUUID()));
    }

    /**
     * @param Especialidad[] $especialidades
     */
    private function repositoy(array $especialidades = []): EspecialidadRepositoryDoctrine
    {
        $especialidadesCount = count($especialidades);
        for ($i = 0; $i < 10 - $especialidadesCount; ++$i) {
            $especialidades[] = $this->createEspecialidad();
        }

        \shuffle($especialidades);

        $em = $this->createStub(EntityManagerInterface::class);
        $em->method('getRepository')->willReturnCallback(
            /** @psalm-return ObjectRepositoryInMemory<Especialidad> */
            static fn (): ObjectRepositoryInMemory => new ObjectRepositoryInMemory(
                $especialidades,
                Especialidad::class
            )
        );

        return new EspecialidadRepositoryDoctrine($em);
    }

    private function createEspecialidad(?string $uuid = null, ?string $nombre = null): Especialidad
    {
        return new Especialidad(
            new EspecialidadId($uuid ?? $this->createUUID()),
            $nombre ?? 'asd'
        );
    }

    private function createUUID(): string
    {
        return (string) Uuid::uuid4();
    }
}
