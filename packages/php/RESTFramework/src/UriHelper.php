<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Utilidades para trabajar el path de la URI de los requests.
 */
trait UriHelper
{
    /**
     * Segmento en el cual buscar el Id del recurso
     */
    private int $resourceIdPathSegment = 4;

    /**
     * Obtiene el Id del path de una URI o lanza una excepción.
     *
     * @throws \Exception excepción lanzada cuando la URI no cuenta con el
     * segmento del path del Id.
     */
    private function getResourceIdFromPath(ServerRequestInterface $request): string
    {
        return $this->findResourceIdFromPath($request) ?? throw new \Exception('No se encontró un ID en la URI');
    }

    /**
     * Busca un Id en el path de una URI.
     */
    private function findResourceIdFromPath(ServerRequestInterface $request): ?string
    {
        $idSegment = explode('/', $request->getUri()->getPath())[$this->resourceIdPathSegment] ?? null;

        if (empty($idSegment)) {
            return null;
        }

        return $idSegment;
    }

    /**
     * Obtiene la URI base del request (scheme+host+port)
     * Ejemplo:
     *  https://localhost:8080/path/segment2/42?query=theanswertolifetheuniverseandeverything -> https://localhost:8080
     */
    private function getBaseUri(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();
        return sprintf(
            $uri->getPort() ? '%s://%s:%s' : '%s://%s',
            $uri->getScheme(),
            $uri->getHost(),
            (string) $uri->getPort()
        );
    }
}
