<?php

declare(strict_types=1);

namespace Consultorio\Core\Presentacion\WebApp;

use Psr\Http\Message\ResponseInterface;

interface WebAppResponseFactoryInterface
{
    public function createResponseFromItem(object $resource, int $code, string $reasonPhrase = ''): ResponseInterface;
}
