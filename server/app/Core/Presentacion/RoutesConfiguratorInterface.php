<?php

declare(strict_types=1);

namespace Consultorio\Core\Presentacion;

use Consultorio\Core\CoreContainer;
use Laminas\ServiceManager\ServiceManager;

interface RoutesConfiguratorInterface
{
    public function __construct(ServiceManager $serviceManager, CoreContainer $coreContainer);

    public static function create(ServiceManager $serviceManager, CoreContainer $coreContainer): static;

    public function configure(): void;
}
