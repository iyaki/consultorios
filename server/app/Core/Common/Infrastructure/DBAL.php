<?php

declare(strict_types=1);

namespace Consultorios\Core\Common\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Persistence\Mapping\Driver\PHPDriver;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class DBAL
{
    private static ?EntityManager $em = null;

    private static ?UnitOfWorkDoctrine $uow = null;

    public function __construct(
        private DoctrineSettings $settings
    ) {
    }

    public function entityManager(): EntityManager
    {
        if (self::$em !== null) {
            return self::$em;
        }

        $doctrineConfig = Setup::createConfiguration($this->settings->devMode);

        $doctrineConfig->setMetadataDriverImpl(
            new PHPDriver($this->settings->mappingsPaths)
        );

        $doctrineConfig->setMetadataCache(
            new ChainAdapter([
                new ArrayAdapter(),
                new FilesystemAdapter(),
            ])
        );

        $dbSettings = $this->settings->dbSettings;
        self::$em = EntityManager::create(
            [
                'driver' => 'pdo_mysql',
                'host' => $dbSettings->host,
                'port' => 3306,
                'dbname' => $dbSettings->database,
                'user' => $dbSettings->user,
                'password' => $dbSettings->password,
                'charset' => 'utf8',
            ],
            $doctrineConfig
        );

        return self::$em;
    }

    public function unitOfWork(): UnitOfWorkDoctrine
    {
        if (self::$uow === null) {
            self::$uow = new UnitOfWorkDoctrine($this->entityManager());
        }

        return self::$uow;
    }
}
