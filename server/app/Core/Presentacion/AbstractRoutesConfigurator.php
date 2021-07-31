<?php

declare(strict_types=1);

namespace Consultorio\Core\Presentacion;

use Consultorio\Core\CoreContainer;
use Laminas\ServiceManager\ServiceManager;

abstract class AbstractRoutesConfigurator implements RoutesConfiguratorInterface
{
    private static self $instance;

    private static bool $initialized = false;

    public function __construct(
        private ServiceManager $container,
        CoreContainer $coreContainer
    ) {
        if (! self::$initialized) {
            throw new \Exception('Para obtener instancias de ' . static::class . ' debe utilizarse el método create()');
        }
    }

    public static function create(ServiceManager $serviceManager, CoreContainer $coreContainer): static
    {
        self::$initialized = true;

        /** @psalm-suppress UnsafeInstantiation */
        self::$instance = new static($serviceManager, $coreContainer);

        self::$initialized = false;

        return self::$instance;
    }

    abstract public function configure(): void;

    protected function container(): ServiceManager
    {
        return $this->container;
    }
}
