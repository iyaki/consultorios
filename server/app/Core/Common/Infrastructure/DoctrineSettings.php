<?php

declare(strict_types=1);

namespace Consultorios\Core\Common\Infrastructure;

final class DoctrineSettings
{
    /**
     * @param string[] $mappingsPaths
     */
    public function __construct(
        public DatabaseConnectionSettings $dbSettings,
        public array $mappingsPaths,
        public bool $devMode
    ) {
    }
}
