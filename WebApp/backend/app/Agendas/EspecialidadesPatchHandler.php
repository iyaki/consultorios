<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\EspecialidadId;
use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\RESTFramework\RequestBodyHelper;
use Consultorios\RESTFramework\ResponseFactory;
use Consultorios\RESTFramework\UriPathSegmentsHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesPatchHandler implements RequestHandlerInterface
{
    use RequestBodyHelper;

    use UriPathSegmentsHelper;

    public function __construct(
        private ResponseFactory $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    /**
     *  @OA\Patch(
     *      path="/webapp/agendas/especialidades/{id}",
     *      operationId="editarEspecialidad",
     *      summary="Edita el registro de una especialidad",
     *      description="Edita los datos de una especialidad ya registrada.",
     *      tags={"Especialidades"},
     *      @OA\Parameter(
     *          in="path",
     *          name="id",
     *          description="ID de la especialidad",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uuid",
     *          ),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              nullable=false,
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  nullable=false,
     *                  ref="#/components/schemas/Especialidad"
     *              ),
     *              required={"data"},
     *              additionalProperties=false,
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Especialidad editada.",
     *          @OA\JsonContent(
     *              type="object",
     *              nullable=false,
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  nullable=false,
     *                  ref="#/components/schemas/Especialidad",
     *              ),
     *              required={"data"},
     *              additionalProperties=false,
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error inesperado.",
     *          @OA\JsonContent(
     *              type="object",
     *              nullable=false,
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  nullable=false,
     *                  ref="#/components/schemas/Error",
     *              ),
     *              required={"data"},
     *              additionalProperties=false,
     *          ),
     *      ),
     *  ),
     */
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
