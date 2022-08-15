<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ExceptionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ExceptionMiddleware
    {
        return new ExceptionMiddleware(new ResponseFactory(
            $container->get(ResponseFactoryInterface::class),
            [
                \Throwable::class => ThrowableTransformer::class,
            ]
        ));
    }
}
