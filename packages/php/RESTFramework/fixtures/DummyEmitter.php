<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\Fixtures;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Clase utilizada en las pruebas unitarias de Application.
 *
 * @internal For tests purposes only
 */
final class DummyEmitter implements EmitterInterface
{
    public ResponseInterface $lastEmittedResponse;

    public function emit(ResponseInterface $response): bool
    {
        return (bool) ($this->lastEmittedResponse = $response);
    }

    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    public function __sleep()
    {
        throw new \Exception('This class can\'t be serialized');
    }
}
