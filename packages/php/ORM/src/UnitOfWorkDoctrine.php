<?php

declare(strict_types=1);

namespace Consultorios\ORM;

use Consultorios\UnitOfWork\UnitOfWorkInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UnitOfWorkDoctrine implements UnitOfWorkInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function beginTransaction(): void
    {
        $this->em->beginTransaction();
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
        $this->em->rollback();
    }
}
