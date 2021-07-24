<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Dominio;

use Consultorio\Agendas\Dominio\Especialidad;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Agendas\Dominio\EspecialidadRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

final class EspecialidadRepositoryDoctrine implements EspecialidadRepositoryInterface
{
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

    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        $limit = null,
        $offset = null
    ): array {
        return $this->repository->findBy($criteria);
    }

    public function add(Especialidad $especialidad): void
    {
        $this->em->persist($especialidad);
    }
}
