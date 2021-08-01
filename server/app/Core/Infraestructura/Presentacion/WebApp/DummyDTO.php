<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

/**
 * @internal For tests purposes only
 */
class DummyDTO
{
    public function __construct(
        public string $property
    ) {
    }
}
