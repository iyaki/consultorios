<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas\Infrastructure;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\Domain\EspecialidadId;
use Consultorios\Core\Agendas\Domain\EspecialidadRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

/**
 * Implementación del repositorio de especialidades utilizando la libreria
 * Doctrine ORM como base.
 */
final class EspecialidadRepositoryDoctrine implements EspecialidadRepositoryInterface
{
    /**
     * @var EntityRepository<Especialidad>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $em
    ) {
        $this->repository = $this->em->getRepository(Especialidad::class);
    }

    public function crearId(): EspecialidadId
    {
        return new EspecialidadId((string) Uuid::uuid4());
    }

    public function get(EspecialidadId $id): Especialidad
    {
        $especialidad = $this->repository->find((string) $id);

        if ($especialidad === null) {
            throw new \UnexpectedValueException(
                'El id ' . $id . ' no corresponde a ninguna especialidad'
            );
        }

        return $especialidad;
    }

    /**
     * @param array<string, mixed> $criteria
     *
     * @return Especialidad[]
     */
    public function findBy(array $criteria): array
    {
        return $this->repository->findBy($criteria);
    }

    public function add(Especialidad $especialidad): void
    {
        $this->em->persist($especialidad);
    }

    public function remove(EspecialidadId $id): void
    {
        $this->em->remove(
            $this->get($id)
        );
    }
}
