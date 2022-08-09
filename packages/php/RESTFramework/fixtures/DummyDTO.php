<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\Fixtures;

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
