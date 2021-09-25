<?php

declare(strict_types=1);

namespace Consultorios\DBAL;

use Consultorios\UnitOfWork\UnitOfWorkInterface;
use Doctrine\ORM\EntityManager;

final class UnitOfWorkDoctrine implements UnitOfWorkInterface
{
    public function __construct(
        private EntityManager $em
    ) {
    }

    public function beginTransaction(): void
    {
        $this->em->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->em->flush();
        if ($this->em->getConnection()->isTransactionActive()) {
            $this->em->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->em->getConnection()->isTransactionActive()) {
            $this->em->rollback();
        }
    }
}
