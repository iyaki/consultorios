<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use League\Fractal\Serializer\DataArraySerializer;

/**
 * Implementación personalizada de un Serizalizer de la libreria
 * PHP League - Fractal para convertir objetos (entidades o excepciones)
 * en respuestas JSON.
 */
final class CustomSerializer extends DataArraySerializer
{
    /**
     * Serialize null resource.
     *
     * @return array{data: null}
     */
    public function null(): array
    {
        return [
            'data' => null,
        ];
    }
}
