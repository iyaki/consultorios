<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Utilidades para trabajar el cuerpo de los requests.
 */
trait RequestBodyHelper
{
    /**
     * Devuelve el segmento "data" del cuerpo (en JSON) de un request.
     *
     * @throws \Exception excepción lanzada al intentar procesar un JSON invalido.
     */
    private function getData(ServerRequestInterface $request): object
    {
        $body = (object) json_decode(
            (string) $request->getBody(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );

        if (!property_exists($body, 'data')) {
            throw new \Exception('Invalid body format');
        }

        if ($body->data === null) {
            throw new \Exception('Invalid body data');
        }

        return (object) $body->data;
    }
}
