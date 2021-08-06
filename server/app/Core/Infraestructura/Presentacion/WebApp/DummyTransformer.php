<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

/**
 * @internal For tests purposes only
 */
class DummyTransformer extends \League\Fractal\TransformerAbstract
{
    /**
     * @return array<string, string>
     */
    public function transform(\Consultorio\Core\Infraestructura\Presentacion\WebApp\DummyDTO $dto): array
    {
        return [
            'property' => $dto->property,
        ];
    }
}
