<?php

declare(strict_types=1);

namespace Consultorio\Core;

use Clockwork\Authentication\NullAuthenticator;
use Clockwork\Clockwork;
use Clockwork\Storage\FileStorage;
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
    private static ?EntityManager $entityManager = null;

    private static ?Clockwork $clockwork = null;

    public function __construct(
        private ContainerInterface $serviceLocator
    ) {
    }

    public function getUnitOfWork(): \Consultorio\Core\Infraestructura\Aplicacion\UnitOfWorkDoctrine
    {
        return new UnitOfWorkDoctrine(
            $this->getEntityManager()
        );
    }

    public function getEntityManager(): EntityManager
    {
        if (self::$entityManager !== null) {
            return self::$entityManager;
        }

        $config = (array) $this->serviceLocator->get('config');

        $doctrineConfig = Setup::createConfiguration(
            (bool) $config['dev_mode']
        );

        $doctrineConfig->setMetadataDriverImpl(
            new PHPDriver([
                __DIR__ . '/../Agendas/Infraestructura/Mappings',
            ])
        );

        $doctrineConfig->setMetadataCache(
            new ChainAdapter([
                new ArrayAdapter(),
                new FilesystemAdapter(),
            ])
        );

        $dbConf = (array) $config['database'];
        $primaryDbConf = (array) $dbConf['primary'];

        self::$entityManager = EntityManager::create(
            [
                'driver' => 'pdo_mysql',
                'host' => $primaryDbConf['host'],
                'port' => 3306,
                'dbname' => $primaryDbConf['database'],
                'user' => $primaryDbConf['user'],
                'password' => $primaryDbConf['password'],
                'charset' => 'utf8',
            ],
            $doctrineConfig
        );

        return self::$entityManager;
    }

    public function clockwork(): Clockwork
    {
        if (self::$clockwork !== null) {
            return self::$clockwork;
        }

        self::$clockwork = new Clockwork();

        self::$clockwork->storage(new FileStorage(__DIR__ . '/../../clockwork'));
        self::$clockwork->authenticator(new NullAuthenticator());

        return self::$clockwork;
    }
}
