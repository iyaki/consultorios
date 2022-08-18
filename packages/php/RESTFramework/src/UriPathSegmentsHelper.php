<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Utilidades para trabajar el path de la URI de los requests.
 */
trait UriPathSegmentsHelper
{
    /**
     * Obtiene el id del path de una url o lanza una excepción.
     *
     * @throws \Exception excepción lanzada cuando la URI no cuenta con el
     * segmento del path del Id.
     *
     * @todo Change name to getResourceId
     */
    private function getId(ServerRequestInterface $request): string
    {
        return $this->findId($request) ?? throw new \Exception('No se encontró un ID en la URI');
    }

    /**
     * Busca un Id en el path de una url.
     *
     * @todo Change name to findResourceId
     */
    private function findId(ServerRequestInterface $request): ?string
    {
        $idSegment = explode('/', $request->getUri()->getPath())[4] ?? null;
        if (empty($idSegment)) {
            return null;
        }
        return $idSegment;
    }
}
