<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use League\Fractal\TransformerAbstract;

final class ThrowableTransformer extends TransformerAbstract
{
    /**
     * @return array<string, int|string>
     *
     * @psalm-return array{message: string, code: string, file: string, line: int, trace: string}
     */
    public function transform(\Throwable $throwable): array
    {
        return [
            'message' => $throwable->getMessage(),
            'code' => (string) $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
        ];
    }
}
