<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Core\Presentacion\WebApp\RequestBodyHelper;
use Consultorio\Core\Presentacion\WebApp\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesPostHandler implements RequestHandlerInterface
{
    use RequestBodyHelper;

    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    /**
     *  @OA\Post(
     *      path="/agendas/webapp/especialidades",
     *      operationId="registrarEspecialidad",
     *      summary="Registra una especialidad",
     *      description="Registra una nueva especialidad a partir de su nombre. No se permiten nombres duplicados.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  nullable=false,
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      nullable=false,
     *                      @OA\Property(
     *                          property="nombre",
     *                          type="string",
     *                      ),
     *                      required={"nombre"},
     *                  ),
     *                  required={"data"},
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Especialidad registrada.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  nullable=false,
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      nullable=false,
     *                      ref="#/components/schemas/Especialidad",
     *                  ),
     *                  required={"data"},
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error inesperado.",
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
     *                      required={"data"},
     *                  ),
     *              ),
     *          ),
     *      ),
     *  ),
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->getData($request);

        $especialidad = $this->especialidades->crear((string) $data->nombre);

        return $this->responseFactory->createResponseFromItem($especialidad, 201);
    }
}
