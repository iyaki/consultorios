<?php

declare(strict_types=1);

namespace Consultorio\Core\Presentacion\WebApp;

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
        $body = (object) json_decode((string) $request->getBody());
        if (
            json_last_error() !== JSON_ERROR_NONE
        ) {
            throw new \Exception('Error Processing Body Request');
        }

        if ($body->data === null) {
            throw new \Exception('Invalid body data');
        }

        return (object) $body->data;
    }
}
