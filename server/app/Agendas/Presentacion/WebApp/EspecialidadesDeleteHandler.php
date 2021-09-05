<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Agendas\Dominio\EspecialidadId;
use Consultorio\Core\Presentacion\WebApp\ResponseFactoryInterface;
use Consultorio\Core\Presentacion\WebApp\UriPathSegmentsHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesDeleteHandler implements RequestHandlerInterface
{
    use UriPathSegmentsHelper;

    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    /**
     *  @OA\Delete(
     *      path="/agendas/webapp/especialidades/{id}",
     *      operationId="eliminarEspecialidad",
     *      summary="Elimina el registro de una especialidad",
     *      description="Elimina una especialidad registrada previamente.",
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
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  nullable=false,
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      nullable=false,
     *                      ref="#/components/schemas/Error",
     *                  ),
     *                  required={"data"},
     *              ),
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
