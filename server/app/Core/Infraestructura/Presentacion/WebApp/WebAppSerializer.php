<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use League\Fractal\Serializer\DataArraySerializer;

final class WebAppSerializer extends DataArraySerializer
{
    /**
     * Serialize null resource.
     *
     * @return array<string, null>
     */
    public function null(): array
    {
        return [
            'data' => null,
        ];
    }
}
