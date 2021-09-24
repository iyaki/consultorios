<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

/**
 * Clase utilizada en las pruebas unitarias de AbstractWebAppResponseFactoryFractal.
 *
 * @internal For tests purposes only
 */
class DummyDTO
{
    public function __construct(
        public string $property
    ) {
    }
}
