<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework\Fixtures;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Attributes as OA;

final class TestPostHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/test',
        description: 'Test',
        tags: ['Test']
    )]
    #[OA\Response(
        response: 400,
        description: 'Error inesperado',
        content: new OA\JsonContent(
            type: 'object',
            nullable: false,
            required: ['data'],
            additionalProperties: false,
            properties:[
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    nullable: false,
                    ref: '#/components/schemas/Error'
                ),
            ]
        )
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new \Exception('Error en tiempo de ejecución');

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
