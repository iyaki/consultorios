<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

/**
 * Clase utilizada en las pruebas unitarias de AbstractWebAppResponseFactoryFractal.
 *
 * @internal For tests purposes only
 */
class DummyTransformer extends \League\Fractal\TransformerAbstract
{
    /**
     * @return array<string, string>
     */
    public function transform(DummyDTO $dto): array
    {
        return [
            'property' => $dto->property,
        ];
    }
}
