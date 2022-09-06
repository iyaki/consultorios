<?php

declare(strict_types=1);

namespace Consultorios\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\Persistence\Mapping\Driver\PHPDriver;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class ORM
{
    public readonly EntityManagerInterface $entityManager;

    public readonly UnitOfWorkInterface $unitOfWork;

    public function __construct(
        DatabaseConnectionSettings $dbSettings,
        array $mappingsPaths,
        bool $devMode
    ) {
        $this->entityManager = $this->createEntityManager(
            $dbSettings,
            $mappingsPaths,
            $devMode
        );

        $this->unitOfWork = $this->createUnitOfWork($this->entityManager);
    }

    private function createEntityManager(
        DatabaseConnectionSettings $dbSettings,
        array $mappingsPaths,
        bool $devMode
    ): EntityManager {
        $doctrineConfig = ORMSetup::createConfiguration($devMode);

        $doctrineConfig->setMetadataDriverImpl(
            new PHPDriver($mappingsPaths)
        );

        $doctrineConfig->setMetadataCache(
            new ChainAdapter([
                new ArrayAdapter(),
                new FilesystemAdapter(),
            ])
        );

        return EntityManager::create(
            $this->connection($dbSettings),
            $doctrineConfig
        );
    }

    private function createUnitOfWork(EntityManagerInterface $entityManager): UnitOfWorkDoctrine
    {
        return new UnitOfWorkDoctrine($entityManager);
    }

    /**
     * @return mixed[]
     */
    private function connection(DatabaseConnectionSettings $dbSettings): array
    {
        return getenv('CI')
            ? $this->connectionInMemorySQLite()
            : $this->connectionMySQL($dbSettings)
        ;
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
    private function connectionMySQL(DatabaseConnectionSettings $dbSettings): array
    {
        return [
            'driver' => 'pdo_mysql',
            'host' => $dbSettings->host,
            'port' => 3306,
            'dbname' => $dbSettings->database,
            'user' => $dbSettings->user,
            'password' => $dbSettings->password,
            'charset' => 'utf8',
        ];
    }
}
