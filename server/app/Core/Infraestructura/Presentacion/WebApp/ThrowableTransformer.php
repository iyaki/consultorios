<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use League\Fractal\TransformerAbstract;

final class ThrowableTransformer extends TransformerAbstract
{
    /**
     * @return array<string, array<string, mixed>>|array<string, string>
     *
     * @psalm-return array{message: string, data: array{code: int|string, file: string, line: int, trace: string}}
     */
    public function transform(\Throwable $throwable): array
    {
        return [
            'message' => $throwable->getMessage(),
            'data' => [
                'code' => $throwable->getCode(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ],
        ];
    }
}
