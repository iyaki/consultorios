<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

final class JsendResourceSerializer extends AbstractJsendSerializer
{
    /**
     * @var string
     */
    private const DATA = 'data';

    /**
     * {@inheritdoc}
     */
    public function collection($resourceKey, array $data): array
    {
        return $this->responseWithStatus([
            self::DATA => $data,
        ], self::STATUS_SUCCESS);
    }

    /**
     * {@inheritdoc}
     */
    public function item($resourceKey, array $data): array
    {
        return $this->responseWithStatus([
            self::DATA => $data,
        ], self::STATUS_SUCCESS);
    }

    /**
     * {@inheritdoc}
     */
    public function null(): array
    {
        return [
            self::DATA => [],
        ];
    }
}
