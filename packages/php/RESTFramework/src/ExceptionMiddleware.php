<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactory $responseFactory
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (\Throwable $throwable) {
            return $this->responseFactory->createResponseFromItem($throwable, 400);
        }
    }
}
