<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

final class JsendErrorSerializer extends AbstractJsendSerializer
{
    public function collection($resourceKey, array $data): void
    {
        throw new \Exception('La especificación JSend no soporta el envío de multipler errores');
    }

    /**
     * @return array<string, mixed>
     */
    public function item($resourceKey, array $data): array
    {
        return $this->responseWithStatus($data, self::STATUS_ERROR);
    }
}
