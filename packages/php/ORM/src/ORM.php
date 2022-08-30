<?php

declare(strict_types=1);

namespace Consultorios\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Persistence\Mapping\Driver\PHPDriver;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class ORM
{
    private ?EntityManager $em = null;

    private ?UnitOfWorkDoctrine $uow = null;

    public function __construct(
        private readonly DatabaseConnectionSettings $dbSettings,
        private readonly array $mappingsPaths,
        private readonly bool $devMode
    ) {
    }

    public function entityManager(): EntityManager
    {
        if ($this->em !== null) {
            return $this->em;
        }

        $doctrineConfig = ORMSetup::createConfiguration($this->devMode);

        $doctrineConfig->setMetadataDriverImpl(
            new PHPDriver($this->mappingsPaths)
        );

        $doctrineConfig->setMetadataCache(
            new ChainAdapter([
                new ArrayAdapter(),
                new FilesystemAdapter(),
            ])
        );

        $this->em = EntityManager::create(
            $this->connection(),
            $doctrineConfig
        );

        return $this->em;
    }

    public function unitOfWork(): UnitOfWorkDoctrine
    {
        if ($this->uow === null) {
            $this->uow = new UnitOfWorkDoctrine($this->entityManager());
        }

        return $this->uow;
    }

    /**
     * @return mixed[]
     */
    private function connection(): array
    {
        return getenv('CI') ? $this->connectionInMemorySQLite() : $this->connectionMySQL();
    }

    /**
     * @return array{driver: string, memory: true}
     */
    private function connectionInMemorySQLite(): array
    {
        return [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];
    }

    /**
     * @return array{driver: string, host: string, port: int, dbname: string, user: string, password: string, charset: string}
     */
    private function connectionMySQL(): array
    {
        return [
            'driver' => 'pdo_mysql',
            'host' => $this->dbSettings->host,
            'port' => 3306,
            'dbname' => $this->dbSettings->database,
            'user' => $this->dbSettings->user,
            'password' => $this->dbSettings->password,
            'charset' => 'utf8',
        ];
    }
}
