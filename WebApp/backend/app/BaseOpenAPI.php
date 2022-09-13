<?php

declare(strict_types=1);

namespace Consultorios\WebApp;

use OpenApi\Attributes as OA;

/**
 * @codeCoverageIgnore
 */
#[OA\Info(
    version: '0.1',
    description: 'API interna de la aplicación web',
    title: 'WebApp'
)]
final class BaseOpenAPI
{
}
