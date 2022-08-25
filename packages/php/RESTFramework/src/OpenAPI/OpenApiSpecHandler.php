<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Attributes as OA;

final class OpenApiSpecHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly string $documentationPath,
        private readonly string $documentationUri,
    ) {}

    #[OA\Server(url: 'https://localhost:8080')]
    #[OA\Get(
        path: 'URI_OPENAPI_PATH_YAML_PLACEHOLDER',
        description: 'Esta documentación',
        tags: ['Documentación']
    )]
    #[OA\Response(
        response: 200,
        description: 'OpenAPI YAML documentation',
        content: new OA\MediaType(
            mediaType: 'application/x-yaml',
            schema: new OA\Schema(type: 'string')
        )
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->yamlSpec($request));

        return $response->withAddedHeader('Content-Type', 'application/x-yaml');
    }

    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    public function __sleep()
    {
        throw new \Exception('This class can\'t be serialized');
    }

    private function yamlSpec(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();
        $serverHost = sprintf(
            '%s://%s:%s',
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
