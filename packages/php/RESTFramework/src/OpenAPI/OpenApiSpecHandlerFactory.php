<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class OpenApiSpecHandlerFactory
{
    public function __invoke(ContainerInterface $container): OpenApiSpecHandler
    {
        return new OpenApiSpecHandler(
            $container->get(ResponseFactoryInterface::class),
            $container->get('documentationParams')['path'],
            $container->get('documentationParams')['uri']
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    /**
     * @codeCoverageIgnore
     */
    public function __sleep()
    {
        throw new \Exception("This class can't be serialized");
    }
}
