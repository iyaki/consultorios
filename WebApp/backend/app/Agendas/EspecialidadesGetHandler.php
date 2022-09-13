<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\UseCases\EspecialidadesInterface;
use Consultorios\RESTFramework\ResponseFactory;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesGetHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly EspecialidadesInterface $especialidades,
    ) {
    }

    #[OA\Get(
        path: '/webapp/agendas/especialidades',
        operationId: 'listarEspecialidades',
        summary: 'Lista las especialidades registradas',
        description: 'Expone una lista de todas las especialidades registradas ordenadas alfabeticamente.',
        tags: ['Especialidades']
    )]
    #[OA\Response(
        response: 200,
        description: 'Lista de especialidades.',
        content: new OA\JsonContent(
            type: 'object',
            nullable: false,
            required: ['data'],
            additionalProperties: false,
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    nullable: false,
                    uniqueItems: true,
                    items: new OA\Items(ref: '#/components/schemas/Especialidad')
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
        $especialidades = $this->especialidades->getAll();
        usort(
            $especialidades,
            static fn (Especialidad $e1, Especialidad $e2): int => $e1->nombre() <=> $e2->nombre()
        );
        return $this->responseFactory->createResponseFromCollection($especialidades);
    }
}
