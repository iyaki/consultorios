<?php

declare(strict_types=1);

namespace Consultorios\Presentations\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\EspecialidadId;
use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\RESTFramework\ResponseFactory;
use Consultorios\RESTFramework\UriPathSegmentsHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesDeleteHandler implements RequestHandlerInterface
{
    use UriPathSegmentsHelper;

    public function __construct(
        private ResponseFactory $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    /**
     *  @OA\Delete(
     *      path="/webapp/agendas/especialidades/{id}",
     *      operationId="eliminarEspecialidad",
     *      summary="Elimina el registro de una especialidad",
     *      description="Elimina una especialidad registrada previamente.",
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
     *      @OA\Response(
     *          response=200,
     *          description="Especialidad eliminada.",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error inesperado",
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
        $this->especialidades->eliminar(new EspecialidadId($id));

        return $this->responseFactory->createResponseFromItem(null, 200);
    }
}
