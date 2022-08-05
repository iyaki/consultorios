<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\EspecialidadId;
use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\RESTFramework\RequestBodyHelper;
use Consultorios\RESTFramework\ResponseFactory;
use Consultorios\RESTFramework\UriPathSegmentsHelper;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesPatchHandler implements RequestHandlerInterface
{
    use RequestBodyHelper;

    use UriPathSegmentsHelper;

    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly Especialidades $especialidades,
    ) {
    }

    #[OA\Patch(
        path: '/webapp/agendas/especialidades/{id}',
        operationId: 'editarEspecialidad',
        summary: 'Edita el registro de una especialidad',
        description: 'Edita los datos de una especialidad ya registrada.',
        tags: ['Especialidades']
    )]
    #[OA\PathParameter(
        name: 'id',
        description: 'ID de la especialidad',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            format: 'uuid'
        )
    )]
    #[OA\RequestBody(
        required: true,
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
                    ref: '#/components/schemas/Especialidad'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Especialidad editada.',
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
        $id = $this->getId($request);
        $data = $this->getData($request);

        $especialidad = $this->especialidades->editar(
            new EspecialidadId($id),
            (string) $data->nombre
        );

        return $this->responseFactory->createResponseFromItem($especialidad, 200);
    }
}
