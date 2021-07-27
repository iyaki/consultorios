<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use League\Fractal\Serializer\ArraySerializer;

abstract class AbstractJsendSerializer extends ArraySerializer
{
    /**
     * @var string
     */
    public const STATUS_SUCCESS = 'success';

    /**
     * @var string
     */
    public const STATUS_FAIL = 'fail';

    /**
     * @var string
     */
    public const STATUS_ERROR = 'error';

    /**
     * @return mixed[]
     */
    protected function responseWithStatus(array $response, string $status): array
    {
        if (
            $status !== self::STATUS_SUCCESS
            && $status !== self::STATUS_FAIL
            && $status !== self::STATUS_ERROR
        ) {
            throw new \Exception(sprintf('%s no es un estado válido según la especificación JSend', $status));
        }

        return array_merge([
            'status' => $status,
        ], $response);
    }
}
