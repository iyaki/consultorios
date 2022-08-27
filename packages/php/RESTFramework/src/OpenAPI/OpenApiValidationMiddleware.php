<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use League\OpenAPIValidation\PSR15\ValidationMiddlewareBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class OpenApiValidationMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private const HTTP_METHOD_OPTIONS = 'options';

    public function __construct(
        private readonly string $documentationPath,
        private readonly string $documentationUri
    ) {
    }

    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    public function __sleep()
    {
        throw new \Exception("This class can't be serialized");
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return
            strtolower($request->getMethod()) === self::HTTP_METHOD_OPTIONS
            ? $handler->handle($request)
            : (
                $this
                    ->openApiValidationMiddleware(
                        $request
                    )->process($request, $handler)
            )
        ;
    }

    private function openApiValidationMiddleware(
        ServerRequestInterface $request
    ): MiddlewareInterface {
        return (new ValidationMiddlewareBuilder())
            ->fromYaml(
                $this->yamlSpec($request)
            )->getValidationMiddleware()
        ;
    }

    private function yamlSpec(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();
        $serverHost = sprintf(
            $uri->getPort() ? '%s://%s:%s' : '%s://%s',
            $uri->getScheme(),
            $uri->getHost(),
            $uri->getPort()
        );

        return (
            new OpenApiGenerator($this->documentationPath)
        )->toYaml(
            $serverHost,
            $this->documentationUri
        );
    }
}
