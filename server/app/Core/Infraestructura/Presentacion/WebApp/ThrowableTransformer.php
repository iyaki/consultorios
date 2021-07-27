<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use League\Fractal\TransformerAbstract;

final class ThrowableTransformer extends TransformerAbstract
{
    /**
     * @return array<string, string>
     */
    public function transform(\Throwable $throwable): array
    {
        return [
            'message' => $throwable->getMessage(),
        ];
    }
}
