<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\OpenAPI;

use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[OA\Server(url: OpenApiGenerator::SERVER_HOST_PLACEHOLDER)]
final class OpenApiSpecHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly string $documentationPath,
        private readonly string $documentationUri
    ) {
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

    #[OA\Get(
        path: OpenApiGenerator::URI_PATH_OPENAPI_YAML_PLACEHOLDER,
        operationId: 'openApiSpec',
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
