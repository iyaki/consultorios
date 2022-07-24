<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\RESTFramework\RequestBodyHelper;
use Consultorios\RESTFramework\ResponseFactory;
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

    /**
     *  @OA\Post(
     *      path="/webapp/agendas/especialidades",
     *      operationId="registrarEspecialidad",
     *      summary="Registra una especialidad",
     *      description="Registra una nueva especialidad a partir de su nombre. No se permiten nombres duplicados.",
     *      tags={"Especialidades"},
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
     *          response=201,
     *          description="Especialidad registrada.",
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
        $data = $this->getData($request);

        $especialidad = $this->especialidades->crear((string) $data->nombre);

        return $this->responseFactory->createResponseFromItem($especialidad, 201);
    }
}
