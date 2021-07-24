<?php

declare(strict_types=1);

namespace Consultorio\Core;

use Consultorio\Core\Aplicacion\UnitOfWorkInterface;
use Consultorio\Core\Infraestructura\Aplicacion\UnitOfWorkDoctrine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Persistence\Mapping\Driver\PHPDriver;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class CoreContainer
{
    private static $entityManager;

    public function __construct(
        private ContainerInterface $serviceLocator
    ) {
    }

    public function getUnitOfWork(): UnitOfWorkInterface
    {
        return new UnitOfWorkDoctrine(
            $this->getEntityManager()
        );
    }

    public function getEntityManager(): EntityManager
    {
        if (self::$entityManager) {
            return self::$entityManager;
        }

        $config = Setup::createConfiguration(
            $this->serviceLocator->get('config')['dev_mode']
        );

        $config->setMetadataDriverImpl(
            new PHPDriver([
                __DIR__ . '/../Agendas/Infraestructura/Mappings',
            ])
        );

        $config->setMetadataCache(
            new ChainAdapter([
                new ArrayAdapter(),
                new FilesystemAdapter(),
            ])
        );

        $dbConf = $this->serviceLocator->get('config')['database']['primary'];

        self::$entityManager = EntityManager::create(
            [
                'driver' => 'pdo_mysql',
                'host' => $dbConf['host'],
                'port' => 3306,
                'dbname' => $dbConf['database'],
                'user' => $dbConf['user'],
                'password' => $dbConf['password'],
                'charset' => 'utf8',
            ],
            $config
        );

        return self::$entityManager;
    }
}
