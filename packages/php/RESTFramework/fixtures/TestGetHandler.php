<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\Fixtures;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '0.1',
    description: 'Test',
    title: 'Test'
)]
final class TestGetHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/test',
        description: 'Test',
        tags: ['Test']
    )]
    #[OA\Response(
        response: 200,
        description: 'Test Response',
        content: new OA\MediaType(
            mediaType: 'text/plain',
            schema: new OA\Schema(type: 'string')
        )
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new TextResponse('Hey!');
    }

    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    public function __sleep()
    {
        throw new \Exception('This class can\'t be serialized');
    }
}
