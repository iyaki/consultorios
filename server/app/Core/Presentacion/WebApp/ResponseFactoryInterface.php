<?php

declare(strict_types=1);

namespace Consultorio\Core\Presentacion\WebApp;

use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function createResponseFromItem(
        ?object $resource,
        int $code = 200,
        string $reasonPhrase = ''
    ): ResponseInterface;

    /**
     * @param object[] $resources
     */
    public function createResponseFromCollection(
        array $resources,
        int $code = 200,
        string $reasonPhrase = ''
    ): ResponseInterface;
}
