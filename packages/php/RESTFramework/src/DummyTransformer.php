<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use League\Fractal\TransformerAbstract;

/**
 * Clase utilizada en las pruebas unitarias de AbstractWebAppResponseFactoryFractal.
 *
 * @internal For tests purposes only
 */
class DummyTransformer extends TransformerAbstract
{
    /**
     * @return array{property: string}
     */
    public function transform(DummyDTO $dto): array
    {
        return [
            'property' => $dto->property,
        ];
    }
}
