<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\RESTFramework\RequestBodyHelper;
use Consultorios\RESTFramework\ResponseFactory;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesPostHandler implements RequestHandlerInterface
{
    use RequestBodyHelper;

    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly Especialidades $especialidades,
    ) {
    }

    #[OA\Post(
        path: '/webapp/agendas/especialidades',
        operationId: 'registrarEspecialidad',
        summary: 'Registra una especialidad',
        description: 'Registra una nueva especialidad a partir de su nombre. No se permiten nombres duplicados.',
        tags: ['Especialidades']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            nullable: false,
            required: ['data'],
            additionalProperties: false,
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    nullable: false,
                    ref: '#/components/schemas/Especialidad'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Especialidad registrada.',
        content: new OA\JsonContent(
            type: 'object',
            nullable: false,
            required: ['data'],
            additionalProperties: false,
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    nullable: false,
                    ref: '#/components/schemas/Especialidad'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Error inesperado',
        content: new OA\JsonContent(
            type: 'object',
            nullable: false,
            required: ['data'],
            additionalProperties: false,
            properties: [
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
        $data = $this->getData($request);

        $especialidad = $this->especialidades->crear((string) $data->nombre);

        return $this->responseFactory->createResponseFromItem($especialidad, 201);
    }
}
