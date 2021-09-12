<?php

declare(strict_types=1);

namespace Consultorios\Presentations\Common\REST\Framework;

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
