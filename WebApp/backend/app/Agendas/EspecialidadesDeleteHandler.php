<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\EspecialidadId;
use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\RESTFramework\ResponseFactory;
use Consultorios\RESTFramework\UriHelper;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesDeleteHandler implements RequestHandlerInterface
{
    use UriHelper;

    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly Especialidades $especialidades,
    ) {
    }

    #[OA\Delete(
        path: '/webapp/agendas/especialidades/{id}',
        operationId: 'eliminarEspecialidad',
        summary: 'Elimina el registro de una especialidad',
        description: 'Elimina una especialidad registrada previamente.',
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
    #[OA\Response(
        response: 200,
        description: 'Especialidad eliminada.'
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
        $id = $this->getResourceIdFromPath($request);
        $this->especialidades->eliminar(new EspecialidadId($id));

        return $this->responseFactory->createResponseFromItem(null, 200);
    }
}
